# RegDoc 

 

Documento responsável por trazer um passo-a-passo de como executar o RegDoc. localmente em sistemas Windows. 

 
### 📋 Pré-Requisitos 

 
* [PHP] - versão mais atualizada (https://windows.php.net/download/) 

* [PostgreSQL] - versão mais atualizada (https://www.postgresql.org/download/windows/) 

* [Composer] - versão mais atualizada (https://getcomposer.org/download/) 



 
 

### 🔧 Instalações & Start Aplicação 

Para ter um ambiente em execução será necessária a instalação e configuração de algumas ferramentas. 

 

* PHP 

``` 

Passo 1) Crie um diretório em seu local de preferência e descompacte o arquivo de download do PHP. 

Passo 2) Instale o PHP seguindo as configurações padrão. 

Passo 3) Edite as variáveis de ambiente do Windows incluindo um novo valor na variável "Path". O valor deve conter a raiz do diretório de instalação do PHP, por exemplo, C:\php-8.1.11. 

Passo 4) Confirme se a instalação do PHP está correta. Acesse qualquer terminal e digite o comando php --version, a versão do PHP deve ser exibida. 


``` 

* Composer 

``` 

Passo 1) Execute o arquivo de download (.exe) para iniciar a instalação. Nenhuma opção deve ser selecionada, ou seja, a instalação deve ser padrão. 

Passo 2) Edite as variáveis de ambiente do Windows incluindo um novo valor na variável "Path". O valor deve conter a raiz do diretório de instalação do PHP, por exemplo, C:\ProgramData\ComposerSetup\bin. Geralmente, o Composer insere automaticamente essa variável.  

Passo 3) Reinicie a máquina para que as configurações sejam aplicadas. 

Passo 4) Confirme se a instalação do Composer está correta. Acesse qualquer terminal e digite o comando composer --version, a versão do PHP deve ser exibida. 

Passo 5) No diretório onde clonou o projeto será necessário instalar o Compose com o comando e composer install e atualiza-lo com o comando composer update 

``` 

* Banco de dados 

``` 

Passo 1) Execute o arquivo de download (.exe) para iniciar a instalação do PostgreSQL. Seguir a instalação padrão, sem a necessidade de modificar nenhuma opção. 

Passo 2) Ao termino do processo, o programa pgAdmin também deve ser instalado. Ele será utilizado para gerenciar o banco de dados. Abra-o e clicando o botão direito, crie um novo Database (sugestão de nome: regdoc).  

Passo 3) Clicando com o botão direito sobre o Database criado, utilize Restore para iniciar o processo de restauração de um banco de dados. Ponto importante: nesse passo é necessário ter acesso ao arquivo de dump do banco de dados do RegDoc. 

Passo 4) Finalizando a restauração do banco já é possível iniciar a aplicação. Ponto importante: geralmente, a restauração do banco de dados gera alguns erros, eles são comuns e não deve afetar o comportamento da aplicação 

``` 

* Start da aplicação 

``` 

Passo 1) Com todos os dados realizados com sucesso é hora de iniciar a aplicação. Navegue até o diretório do projeto e, através de um terminal de comando, digite o comando php artisan serve. Se tudo estiver correto deve ser exibido a mensagem Starting Laravel development server: http://127.0.0.1:8000 

Passo 2) Abra o navegador utilizando a url http://127.0.0.1:8000  

``` 

 # regdoc
