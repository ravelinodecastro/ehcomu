<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use App\Models\Donation;
    use App\User;
    use JWTAuth;
    use DB;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use PayPal\Api\Amount;
    use PayPal\Api\Details;
    use PayPal\Api\Item;
    use PayPal\Api\ItemList;
    use PayPal\Api\Payer;
    use PayPal\Api\Payment;
    use PayPal\Api\PaymentExecution;
    use PayPal\Api\RedirectUrls;
    use PayPal\Api\Transaction;
    use PayPal\Auth\OAuthTokenCredential;
    use PayPal\Rest\ApiContext;


    class DonationController extends Controller
    {
        private $_api_context;
        public function __construct(){
            $paypal_conf = \Config::get('paypal');
            $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
            );
            $this->_api_context->setConfig($paypal_conf['settings']);
            
        }
        public function get(Request $request){
            $donations = Donation::whereId($request->user()->id)->get();
            return response()->json(['message' => "Operação realizada com sucesso.", 'donations' => $donations, 'success' => true]);
        }
        public function donatePaypal(Request $request) {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $item_1 = new Item();
            $item_1->setName('Doação para EhComu')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($request->amount);
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            $amount = new Amount();
            $amount->setCurrency('USD')
            ->setTotal($request->amount);
            $transaction = new Transaction();
            $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Doação para EhComu - Be grand');
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(env('APP_URL').'/donation-confirm')
            ->setCancelUrl(env('APP_URL').'/donation-confirm');
            $payment = new Payment();
            $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
            try {
                $payment->create($this->_api_context);
            } catch (\Paypal\Exception\PPConectionExecption $ex) {
               if (\Config::get('app.debug')){
                return response()->json(['message' => 'Falha na conexão.',  'success' => false]);
               }
               else{
                return response()->json(['message' =>'Falha ao tentar realizar a doação.',  'success' => false]);
                }
            }
            foreach ($payment->getLinks() as $link) {
                if($link->getRel()== 'approval_url'){
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            if (isset($redirect_urls)){
                return response()->json(['message' => "Operação realizada com sucesso.", 'url'=>$redirect_url, 'success' => true]);
            }
            return response()->json(['message' =>'Erro desconhecido.',  'success' => false]);
            
        }
        public function checkPaypal(Request $request) {
            DB::beginTransaction();
            try {

                $payment = Payment::get($request->payment_id, $this->_api_context);
                $execution = new PaymentExecution();
                $execution->setPayerId(Input::get('PayerID'));
        
                /**Execute the payment **/
                $result = $payment->execute($execution, $this->_api_context);
        
                if ($result->getState() == 'approved') {
        
                    $donation = Donation::create([
                        'reference' => $request->payment_id,
                        'via' =>'paypal',
                        'amount' =>$request->amount,
                        'user_id'=>$request->user()?$request->user()->id :''
                    ]);
                    DB::commit();
                    return response()->json(['message' => "Operação realizada com sucesso.", 'success' => true]);
        
                }
        
                DB::rollback();
                return response()->json(['message' => "Falha ao realizar essa operação.", 'success' => false]);
            } catch (Exception $e){
                DB::rollback();
                return response()->json(['message'=>'Falha ao relizar a operação', 'success' => false]);
            }
        }
        
    }