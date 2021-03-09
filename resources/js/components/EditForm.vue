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
      <form :action="formInfo.rote" method="post" @submit.prevent="send">
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
              v-model="formInfo.server.invite"
              placeholder="https://discord.gg/abcdfgh"
            />
          </div>
          <input type="hidden" name="csrf" :value="formInfo.csrf" />
          <label class="col-sm-4 col-form-label" for="description"
            >uma breve descrição sobre seu servidor</label
          >
          <div class="input-group col-sm-8">
            <input
              class="form-control"
              type="text"
              id="description"
              v-model="formInfo.server.description"
              name="description"
              placeholder="Um servidor amigável"
            />
            <div class="input-group-prepend">
              <div
                class="input-group-text"
                v-bind:class="{ 'text-danger': descTooLong }"
              >
                {{ counter }}
              </div>
            </div>
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
export default {
  name: "Edit",
  props: {
    content: String,
  },
  data() {
    return {
      formInfo: {
        server: {
          invite: "",
          description: "",
          tags: [],
        },
        allTags: [],
        rote: "",
        csrf: "",
      },
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
    send() {
      let canSend = true;
      let description = this.formInfo.server.description;
      let invite = this.formInfo.server.invite;

      if (description == "" || invite == "") {
        this.sendAlert(0, "Preencha todos os campos do formuário");
        canSend = false;
      }

      if (description.length > 140) {
        this.sendAlert(0, "Sua descrição é muito grande");
        canSend = false;
      }

      if (canSend === true) {
        let data = new FormData();
        data.append("invite", invite);
        data.append("description", description);
        data.append("csrf", this.formInfo.csrf);
        fetch(this.formInfo.rote, {
          method: "POST",
          body: data,
        }).then((resp) => {
          resp.text().then((code) => {
            switch (code) {
              case "0":
                this.sendAlert(0, "Erro ao salvar, tente novamente mais tarde");
                break;
              case "1":
                this.sendAlert(1, "Edição efeituada com sucesso!");
                break;
              case "2":
                this.sendAlert(0, "Preencha todos os campos do formuário");
                break;
              case "3":
                this.sendAlert(0, "Sua descrição é muito grande");
                break;
              case "23":
                this.sendAlert(0, "Preencha todos os campos do formuário");
                this.sendAlert(0, "Sua descrição é muito grande");
                break;
              default:
                this.sendAlert(0, code);
                break;
            }
          });
        });
      }
    },
  },
  computed: {
    counter: function () {
      return 140 - this.formInfo.server.description.length;
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
      this.formInfo = JSON.parse(this.content);
    }
  },
};
</script>

<style scoped>
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