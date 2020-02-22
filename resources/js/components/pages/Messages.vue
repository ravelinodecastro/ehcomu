<template>
  <div class="message-container">
    <div id="frame">
      <div id="sidepanel">
        <div id="profile">
          <div class="wrap">
            <router-link to="/profile">
              <img
                id="profile-img"
                :src="currentUser.avatar ||' img/noPerson.png'"
                class="online"
                alt
              />
            </router-link>

            <p>{{currentUser.name}}</p>
          </div>
        </div>
        <div id="search">
          <label for>
            <i class="fa fa-search" aria-hidden="true"></i>
          </label>
          <input type="text" v-model="inputSearch" placeholder="Pesquisar conversas..." />
        </div>
        <div id="contacts" ref="contacts">
          <p
            class="text-white text-center p-4"
            v-if="conversations.length==0 && loading == false"
          >Nenhum registro de mensagem</p>
          <p
            class="text-white text-center p-4"
            v-else-if="conversationsFiltered.length==0 && conversations.length!=0  && loading == false"
          >Mensagem não encontrada</p>
          <p class="text-white text-center p-4" v-else-if=" loading ">Carregando...</p>
          <ul v-if="conversationsFiltered.length!=0">
            <li
              v-for="(message) in conversationsFiltered"
              :key="message.permut_id"
              class="contact"
              :class="{active:conversation_id==message.id}"
            >
              <a @click.prevent="open(message.id)">
                <div class="wrap">
                  <span :class="{online:message.isOnline}" class="contact-status online"></span>
                  <img :src="message.avatar ||'img/noPerson.png'" alt />
                  <div class="meta">
                    <p class="name">{{message.name}}</p>
                    <p class="preview">{{message.isSender?'Você: ':''}}{{message.body}}</p>
                  </div>
                </div>
              </a>
            </li>
          </ul>
        </div>
        <div id="bottom-bar">
          <router-link to="/" class="button-bottom col-12">
            <i class="fa fa-home fa-fw" aria-hidden="true"></i>
            <span>
              Voltar para a Página
              incial
            </span>
          </router-link>
        </div>
      </div>
      <div class="content" ref="content">
        <p
          class="text-dark text-center p-4"
          v-if="conversation_id && messages.length==0 && loadingM == false"
        >Nenhum registro de mensagem</p>

        <p class="text-dark text-center p-4" v-else-if=" loadingM ">Carregando...</p>
        <p
          class="text-dark text-center p-4"
          v-else-if="conversation_id ==null && messages.length==0 && loadingM == false"
        >Clica em uma dar suas</p>
        <div class="contact-profile" v-if="info">
          <img :src="info.avatar || 'img/noPerson.png'" alt />
          <p>{{info.name}}</p>
          <div class="social-media">
            <a :href="'https://wa.me/'+info.whatsapp" style="color: inherit;">
              <i class="fa fa-whatsapp" aria-hidden="true"></i>
            </a>
            <a :href="'mailto:'+info.email" style="color: inherit;">
              <i class="fa fa-envelope" aria-hidden="true"></i>
            </a>
          </div>
        </div>
        <div class="messages" v-if="messages !=[]">
          <ul>
            <li
              :class="{replies: message.isSender, sent: message.isSender==false}"
              v-for="(message, idx) in messagesFiltered"
              :key="idx"
            >
              <small class="date-message" v-if="displayData(idx)">{{formatDate(message.created_at)}}</small>
              <img
                :src="message.isSender? currentUser.avatar ||'img/noPerson.png':message.avatar ||'img/noPerson.png'"
                alt
              />
              <p
                data-toggle="tooltip"
                data-placement="left"
                :title="formatDate(message.created_at)"
              >{{message.body}}</p>
            </li>
          </ul>
        </div>

        <div class="message-input" v-if="conversation_id && loadingM==false && info">
          <div class="col-12 d-flex justify-content-center" v-if="info.status ==0">
            <a
              href="#"
              @click.prevent="updatePermut(info.isRequester)"
              :class="{'btn-outline-danger':info.isRequester==false, 'btn-outline-success':info.isRequester}"
              class="m-2 btn btn-sm"
            >{{info.isRequester?'Minha permuta foi aceite':'Negar o pedido'}}</a>
          </div>
          <div class="wrap" v-if="info.status ==0">
            <input
              type="text"
              v-model="body"
              placeholder="Escreva a sua mensagem..."
              @keyup.enter="create"
            />
            <i class="fa fa-paperclip attachment" aria-hidden="true"></i>
            <button class="submit" @click.prevent="create">
              <i class="fa fa-paper-plane" aria-hidden="true"></i>
            </button>
          </div>
          <p
            class="text-dark text-center p-4"
            v-if="info.status !=0"
          >Não podes responder está conversa. Ela foi {{info.status==1?'realizada':'cancelada'}}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from "sweetalert2";
export default {
  mounted() {
    this.$store.dispatch("fetchUser");
    this.get();
  },
  data() {
    return {
      conversations: [],
      messages: [],
      conversation_id: null,
      info: null,
      loading: false,
      loadingM: false,
      inputSearch: null,
      body: null
    };
  },
  methods: {
    displayData(idx) {
      let previous = this.messagesFiltered[idx - 1]
        ? this.formatDate(this.messagesFiltered[idx - 1].created_at)
        : this.messagesFiltered[idx - 1];
      let current = this.messagesFiltered[idx]
        ? this.formatDate(this.messagesFiltered[idx].created_at)
        : this.messagesFiltered[idx];
      let next = this.messagesFiltered[idx + 1]
        ? this.formatDate(this.messagesFiltered[idx + 1].created_at)
        : this.messagesFiltered[idx + 1];
      if (
        (previous == undefined && current == next) ||
        (current != previous && current != next)
      ) {
        return true;
      }
      return false;
    },
    socketPusher(id) {
       console.log(id+' conectou');
      window.Echo.private("message-channel-private." + id).listen(
        "MessageEventPrivate",
        event => {
          console.log(event);
          if (event.message) {
            let index = null,
              array = this.conversations;
            array.forEach(function(element, idx) {
              if (element.id == event.conversation.id) {
                index = idx;
              }
            });
            this.$delete(array, index);
            let newObj = {
              id: event.message.id,
              body: event.message.body,
              isSender: event.message.isSender,
              isRequester: event.message.isRequester,
              isOnline: event.message.isOnline,
              status: "0",
              permut_id: event.message.conversation_id,
              sender_id: event.message.sender_if,
              name: event.message.name,
              avatar: event.message.avatar,
              created_at: this.dateISOToLocal(),
              updated_at: this.dateISOToLocal()
            };
            this.messages = [...this.messages, newObj];
            this.conversations = [...array, newObj];
          }
          $(function() {
            $('[data-toggle="tooltip"]').tooltip();
          });
        }
      );
    },
    get() {
      this.loading = true;
      let loader = this.$loading.show({
        // Optional parameters
        container: this.fullPage ? null : this.$refs.contacts,
        canCancel: false,
        loader: "dots",
        color: "#238238"
      });
      axios
        .get(`/user/conversations`)
        .then(response => {
          if (response.data.success) {
            this.conversations = _.uniqBy(
              response.data.conversations.data,
              "permut_id"
            );
            this.conversations.forEach(element => {
              this.socketPusher(element.id);
            });
          } else {
            console.log(response.data);
            Swal.fire("Erro!", "Falha ao realizar a operação", "error");
          }
        })
        .catch(err => {
          Swal.fire("Erro!", "Falha ao realizar a operação", "error");
        })
        .finally(() => {
          this.loading = false;
          loader.hide();
          if (this.conversations != []) {
            this.open(this.conversations[0].id);
          }
        });
    },
    open(conversation_id) {
      if (this.conversation_id != conversation_id) {
        this.conversation_id = conversation_id;
        this.loadingM = true;
        let loader = this.$loading.show({
          // Optional parameters
          container: this.fullPage ? null : this.$refs.content,
          canCancel: false,
          loader: "dots",
          color: "#238238"
        });
        axios
          .get(`/user/messages/${this.conversation_id}`)
          .then(response => {
            if (response.data.success) {
              this.messages = response.data.messages.data;
              this.info = response.data.info;
            } else {
              Swal.fire("Erro!", "Falha ao realizar a operação", "error");
            }
          })
          .catch(err => {
            Swal.fire("Erro!", "Falha ao realizar a operação", "error");
          })
          .finally(() => {
            this.loadingM = false;
            loader.hide();
            $(function() {
              $('[data-toggle="tooltip"]').tooltip();
            });
          });
      }
    },
    create() {
      if (this.conversation_id && this.body) {
        axios
          .post(`/user/message-create`, {
            body: this.body,
            permut_id: this.conversation_id
          })
          .then(response => {
            if (response.data.success) {
            } else {
              Swal.fire("Erro!", "Falha ao realizar a operação", "error");
            }
          })
          .catch(err => {
            Swal.fire("Erro!", "Falha ao realizar a operação", "error");
          })
          .finally(() => {
            this.messages = [
              ...this.messages,
              {
                id: this.conversation_id,
                body: this.body,
                isSender: true,
                isRequester: this.info.isRequester,
                isOnline: true,
                status: "0",
                permut_id: this.conversation_id,
                sender_id: this.currentUser.id,
                name: this.info.name,
                avatar: this.info.avatar,
                created_at: this.dateISOToLocal(),
                updated_at: this.dateISOToLocal()
              }
            ];
            this.body = null;
            $(function() {
              $('[data-toggle="tooltip"]').tooltip();
            });
          });
      } else if (this.body == null) {
        Swal.fire("Erro!", "Não podes enviar uma mensagem vazia", "error");
      }
    },

    formatDate(input) {
      let operation = parseInt((new Date() - new Date(input)) / 60000),
        datePart = input.match(/\d+/g),
        year = datePart[0].substring(0),
        month = datePart[1],
        day = datePart[2],
        hour = datePart[3],
        min = datePart[4];

      let hour_parsed = parseInt(operation / 60);
      if (operation < 60) {
        return  "Há " +operation + " min";
      } else if (hour_parsed < 6) {
        return "Há " + hour_parsed + (hour_parsed == 1 ? " hora" : " horas");
      } else {
        return day + "/" + month + "/" + year + " " + hour + ":" + min;
      }
    },
    dateISOToLocal(input) {
      let d = null;
      if (input) {
        d = new Date(input);
      } else {
        d = new Date();
      }

      if (d) {
        let datePart = d.toLocaleString().match(/\d+/g);
        if (datePart && datePart.length >= 5) {
          let day = datePart[0].substring(0),
            month = datePart[1],
            year = datePart[2],
            hour = datePart[3],
            min = datePart[4],
            sec = datePart[5];
          return (
            year + "-" + month + "-" + day + " " + hour + ":" + min + ":" + sec
          );
        }
      }
    },
    updatePermut() {
      if (this.conversation_id) {
        this.loadingM = true;
        let loader = this.$loading.show({
          // Optional parameters
          container: this.fullPage ? null : this.$refs.content,
          canCancel: false,
          loader: "dots",
          color: "#238238"
        });
        axios
          .put(`/user/permut-update`, {
            id: this.conversation_id,
            status: this.info.isRequester ? "1" : "2"
          })
          .then(response => {
            if (response.data.success) {
              this.info.status = this.info.isRequester ? "1" : "2";
            } else {
              Swal.fire("Erro!", "Falha ao realizar a operação", "error");
            }
          })
          .catch(err => {
            Swal.fire("Erro!", "Falha ao realizar a operação", "error");
          })
          .finally(() => {
            this.loadingM = false;
            loader.hide();
          });
      }
    },
    logout() {
      this.$store.commit("logoutUser");
      this.$router.push("/login");
    }
  },
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    },
    messagesFiltered() {
      return this.messages;
    },
    conversationsFiltered() {
      return this.conversations.filter(conversation => {
        if (this.inputSearch) {
          return conversation.name
            .toLowerCase()
            .includes(this.inputSearch.toLowerCase());
        } else {
          return true;
        }
      });
    }
  }
};
</script>