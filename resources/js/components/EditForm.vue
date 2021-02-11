<template>
  <div class="form-area">
    <div :class="item.class" v-for="item in code" :key="item.message">
      <h4>
        {{ item.message }}
      </h4>
    </div>
    <br />
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
        <input type="hidden" name="csrf" value="{}" />
        <label class="col-sm-4 col-form-label" for="description"
          >uma breve descrição sobre seu servidor</label
        >
        <div class="col-sm-8">
          <input
            class="form-control"
            type="text"
            id="description"
            v-model="formInfo.server.description"
            name="description"
            placeholder="Um servidor amigável"
          />
        </div>
        <div class="col-sm m-3">
          <input class="btn btn-primary" type="submit" value="Editar" />
        </div>
      </div>
    </form>
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
        },
        rote: "",
      },
      code: [],
    };
  },
  methods: {
    send(e) {
      let data = new FormData();
      data.append("invite", this.formInfo.server.invite);
      data.append("description", this.formInfo.server.description);
      fetch(this.formInfo.rote, {
        method: "POST",
        body: data,
      }).then((resp) => {
        resp.text().then((code) => {
          switch (code) {
            case "0":
              this.code.push({
                class: "badge badge-danger",
                message: "Erro ao salvar, tente novamente mais tarde",
              });

              break;
            case "1":
              this.code.push({
                class: "badge badge-success",
                message: "Edição efeituada com sucesso!",
              });
              break;
            case "2":
              this.code.push({
                class: "badge badge-danger",
                message: "Preencha todos os campos do formuário",
              });
              break;
            case "3":
              this.code.push({
                class: "badge badge-danger",
                message: "Sua descrição é muito grande",
              });
              break;
            case "23":
              this.code.push({
                class: "badge badge-danger",
                message: "Preencha todos os campos do formuário",
              });
              this.code.push({
                class: "badge badge-danger",
                message: "Sua descrição é muito grande",
              });
              break;
          }

          setTimeout(() => {
            this.code.splice(0, this.code.length);
          }, 5000);
        });
      });
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
input {
  width: 250px;
}

.form-area {
  display: flex;
  height: 80vh;
  align-items: center;
}
</style>
