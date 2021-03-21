<template>
  <div class="content">
    <transition-group name="bounce" class="messages-block">
      <div :class="'message ' + item.class" v-for="item in code" :key="item.id">
        <span>
          {{ item.message }}
        </span>
      </div>
    </transition-group>
    <div class="form-area">
      <form :action="rote" method="post" @submit.prevent="verify">
        <div class="w-75 m-auto form-group row">
          <label class="col-sm-4 col-form-label" for="invite">
            Convite para o servidor(Tem que ser permanente)
          </label>
          <div class="col-sm-8">
            <input
              class="form-control"
              type="text"
              id="invite"
              name="invite"
              v-model="server.invite"
              placeholder="https://discord.gg/abcdfgh"
            />
          </div>

          <label class="col-sm-4 col-form-label" for="description">
            uma breve descrição sobre seu servidor
          </label>
          <div class="input-group col-sm-8">
            <input
              class="form-control"
              type="text"
              id="description"
              v-model="server.description"
              name="description"
              placeholder="Um servidor amigável"
            />
            <div class="input-group-prepend">
              <div
                class="input-group-text"
                :class="{ 'text-danger': descTooLong }"
              >
                {{ counter }}
              </div>
            </div>
          </div>

          <label class="col-sm-4 col-form-label" for="tags"> Tags </label>

          <div class="input-group col-sm-8">
            <Multiselect
              v-model="server.tags"
              :options="allTags"
              track-by="name"
              :custom-label="(e) => e.name"
              :multiple="true"
            />
          </div>

          <div class="col-sm m-3">
            <input class="btn btn-primary" type="submit" value="Editar" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";

export default {
  name: "EditForm",
  components: {
    Multiselect,
  },
  props: {
    content: String,
  },
  data() {
    return {
      server: {
        invite: "",
        description: "",
        tags: [],
      },
      staticServer: "",
      allTags: [],
      rote: "",
      csrf: "",
      code: [],
    };
  },
  methods: {
    sendAlert(type, msg) {
      type = type == 0 ? "danger" : "success";
      this.code.push({
        id: new Date().getTime(),
        class: "badge badge-" + type,
        message: msg,
      });
      setTimeout(() => {
        this.code.splice(0, this.code.length);
      }, 3000);
    },
    verify() {
      let server = JSON.stringify(this.server);
      let staticServer = this.staticServer;

      if (server === staticServer) {
        this.sendAlert(0, "Faça alguma alteração para conseguir enviar");
        return;
      }

      for (let item in JSON.parse(server)) {
        if (JSON.parse(server)[item] == "") {
          this.sendAlert(0, "Preencha todos os campos do formuário");
          return;
        }
      }

      if (this.server.description.length > 140) {
        this.sendAlert(0, "Sua descrição é muito grande");
        return;
      }

      this.send(server, staticServer);
    },
    send(server, staticServer) {
      let data = new FormData();
      data.append("server", server);
      data.append("static", staticServer);
      data.append("all-tags", JSON.stringify(this.allTags));

      fetch(this.rote, {
        method: "POST",
        body: data,
      }).then((resp) => {
        resp.text().then((json) => {
          console.log(json);
          json = JSON.parse(json);
          if (json.edit === "succsess") {
            this.staticServer = server;
            this.sendAlert(1, json.message);
          } else {
            json.message.forEach((msg) => {
              this.sendAlert(0, msg);
            });
          }
        });
      });
    },
  },
  computed: {
    counter: function () {
      return 140 - this.server.description.length;
    },
    descTooLong: function () {
      if (this.counter < 0) {
        return true;
      }
      return false;
    },
  },
  created() {
    if (this.content) {
      let content = JSON.parse(this.content);
      this.server = content.server;
      this.staticServer = JSON.stringify(content.server);
      this.allTags = content.allTags;
      this.rote = content.rote;
      this.csrf = content.csrf;
    }
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style >
.multiselect__tag-icon:focus,
.multiselect__tag-icon:hover {
  background: #ff6a6a;
}
.multiselect__tag,
.multiselect__option--highlight::after,
.multiselect__option--highlight {
  background: #4e6c9d;
}

.multiselect__tag-icon:after {
  color: #3a5279;
}

.server-title {
  display: flex;
  margin: 2%;
  align-items: center;
}
.server-title h1 {
  margin-left: 1%;
}

.form-area {
  display: flex;
  height: 75vh;
  align-items: center;
}
.messages-block {
  display: flex;
  flex-direction: column;
  width: 40%;
  margin: auto;
  text-align: center;
  font-size: 1.4rem;
}
.message {
  margin: 2px;
  padding: 3%;
}
.bounce-enter-active {
  animation: bounce-in 0.5s;
}
.bounce-leave-active {
  animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.5);
  }
  100% {
    transform: scale(1);
  }
}
</style>