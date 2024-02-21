## Projeto REGDOC - Registro Eletrônico

O REGDOC é uma solução inovadora desenvolvida pela Valid Hub para facilitar o registro eletrônico de contratos no Brasil, abrangendo tanto bens imóveis quanto bens móveis. Esta plataforma integra de forma eficiente sistemas cartoriais, financeiros e órgãos públicos, proporcionando uma abordagem ampla, organizada e completamente eletrônica para formalização de registros.


## Tecnologias Utilizadas

- **PHP 8.1**: Linguagem de programação principal.
- **Laravel 8**: Framework PHP para desenvolvimento web.
- **PostgreSQL**: Banco de dados relacional.
- **Bootstrap**: Framework de front-end para desenvolvimento de interfaces responsivas.
- **API RESTful**: Arquitetura utilizada para comunicação entre sistemas.
- **DDD (Domain-Driven Design)**: Padrão de design para modelagem de domínio de negócios.
- **SOLID**: Princípios de design para escrever código limpo e escalável.
- **Migrations**: Ferramenta do Laravel para controle de esquema de banco de dados.
- **Facades**: Componente do Laravel para acesso simplificado aos serviços do framework.
- **Form Request**: Recurso do Laravel para validação de dados de entrada.

## Funcionalidades Principais

- **Integração Tecnológica**: Conecta imobiliárias, instituições financeiras, cartórios extrajudiciais, incorporadoras, etc.
- **Formalização Eletrônica**: Todo o processo de registro é realizado de forma eletrônica, sem uso de papel.
- **Segurança Jurídica**: Utilização de certificado digital garante segurança e validade jurídica aos registros.
- **Agilidade e Eficiência**: Simplifica e agiliza o processo de formalização de contratos, garantindo uma experiência eficiente para os usuários.

## Como Utilizar

1. Faça o clone deste repositório para sua máquina local.
2. Instale as dependências do projeto utilizando o Composer:

   ```
   composer install
   ```

3. Configure as variáveis de ambiente no arquivo `.env`, incluindo as informações de conexão com o banco de dados e as chaves de API, se necessário.
4. Execute as migrations para criar as tabelas no banco de dados:

   ```
   php artisan migrate
   ```

5. Inicie o servidor local:

   ```
   php artisan serve
   ```

6. Acesse a aplicação em seu navegador, geralmente em [http://localhost:8000](http://localhost:8000).



