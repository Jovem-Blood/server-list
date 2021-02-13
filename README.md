# Sobre o projeto
Esse é um projeto privado que eu estava fazendo, era pra ser um desafio simples e acabou virando isso que você está vendo, era para ser algo tranquilo e descontraído, porém, eu decidi complicar as coisas e adicionar tudo (ou quase tudo) que eu aprendi nesse últimos meses, o projeto é basicamente uma lista de servidores do discord, onde o usuário pode adicionar um bot (ainda vou fazer essa parte) no servidor e preencher um formulário com uma descrição, um convite permanente, algumas tags referentes ao servidor e done! O servidor agora irá aparecer no site, as pessoas poderão encontrar o servidor pesquisando por ele, ou pesquisando pela tag. Se você ainda não entendeu, só olhar [Este site](https://top.gg/servers), foi o site que eu me inspirei para criar o projeto.

# Tecnologias usadas
Como eu tinha dito anteriormente, era pra ser algo simples, mas eu fiquei adicionando coisas para fazer e deixar ainda mais complicado (pelo menos está complicado para mim), provavelmente porque eu não planejei essas alterações logo no início do projeto, então agora está uma bagunça. Enfim, aqui está a lista das coisas que eu estou usando atualmente no projeto.

- PHP 7.4
- MySQL
- Vue 2 (porque eu ainda não entendi como funciona o 3 ;-;)
- Bootstrap 4

# Explicando sobre as dependências
Se você olhar o projeto verá que eu estou usando alguns componentes do [Packagist](https://packagist.org/), se você não sabe o que é considere ele como o npm do JavaScript ;), os componentes me ajudam com as rotas, com o banco de dados e a gerar o html (daria pra fazer isso sem componentes mas eu me acostumei a usá-los). Sobre JavaScript, além do vue e os seus respectivos loaders, eu também usei o webpack para fazer o trabalho de converter e minificar os arquivos .vue, para fazer isso você iria precisar configurar o webpack, é bem chato fazer isso na mão e faria mais sentido usar um framework como o Laravel ou Symfony, mas nessa altura eu não podia mais voltar e fazer usando o framework (fora que o desafio era fazer isso tudo sem usar nenhum framework) ¯\\\_(ツ)_/¯.

# Avisos rápidos
- Sobre a estrutura do site em si, eu não sou nenhum mestre do CSS então releve a feiura\falta de responsividade, então vou ficar devendo essa ;).
- O backend é um monolito básico em MVC, ou seja, um request chama tal rota a rota chama um controlador e o controlador faz o que tem que fazer e gera um html.
- Eu usei o Vue em lugares específicos como formulários e os timers.
- O site ainda não está completo, falta bastante coisa na verdade.
- O bot ainda está sendo feito também, tô fazendo tudo sozinho desculpe :v

