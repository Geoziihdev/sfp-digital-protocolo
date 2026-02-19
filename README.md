# SFP Digital - Sistema de Protocolo Eletrônico

O SFP Digital é uma plataforma desenvolvida para modernizar o atendimento ao cidadão, permitindo a abertura e o acompanhamento de protocolos administrativos de forma inteiramente digital. O projeto visa otimizar a comunicação entre a administração pública e a população, garantindo agilidade e transparência nos processos.

## Tecnologias e Arquitetura

O projeto foi estruturado utilizando tecnologias que priorizam a portabilidade e a eficiência de processamento:

* **Front-end:** Interface construída com HTML5 e Tailwind CSS, focada em responsividade e usabilidade.
* **Back-end:** Lógica de negócio desenvolvida em PHP 8, responsável pela gestão de sessões e manipulação de dados.
* **Persistência de Dados:** Utilização de arquivos JSON para armazenamento de informações. Esta escolha técnica permite que o sistema seja executado sem a necessidade de configuração imediata de um banco de dados relacional, facilitando demonstrações e testes de conceito.
* **Gráficos Vetoriais:** Logotipos e ícones implementados via código SVG, garantindo alto desempenho e fidelidade visual em diferentes resoluções de tela.

## Funcionalidades do Sistema

* **Autenticação de Usuários:** Módulo de cadastro e login com gerenciamento de acesso restrito.
* **Gestão de Protocolos:** Formulário para registro de demandas com geração automática de identificadores únicos.
* **Acompanhamento de Status:** Painel de controle para o cidadão verificar o andamento de suas solicitações (Ex: Recebido, Em Análise, Concluído).
* **Segurança e Filtros:** Implementação de lógica de filtragem no servidor, assegurando que cada usuário tenha acesso exclusivo aos seus próprios registros.

## Estrutura do Projeto

* `index.php`: Dashboard principal e listagem de protocolos.
* `login.php`: Interface e lógica de autenticação de usuários.
* `logout.php`: Encerramento seguro de sessão.
* `cadastro.php` / `login.php`: Fluxos de controle de acesso.
* `usuarios.json` / `protocolos.json`: Arquivos de persistência de dados.
* `script.js`: Lógica de interface e interatividade.
* `style.css`: Estilizações customizadas.

## Planejamento de Melhorias

Para futuras iterações do sistema, estão previstas as seguintes atualizações técnicas:

1. **Segurança Avançada:** Implementação de criptografia nativa do PHP para o armazenamento de credenciais, visando conformidade com a LGPD.
2. **Integração com Banco de Dados:** Transição da camada de persistência para MySQL utilizando a biblioteca PDO.
3. **Módulo de Anexos:** Suporte para upload de evidências e documentos complementares em formato PDF ou imagem.
4. **Notificações:** Sistema de alerta via e-mail para atualizações de status de protocolo.
5. **Módulo Administrativo:** Criação de interface de gestão para triagem e despacho de demandas por servidores públicos.

---

## Autor

Desenvolvido por **[Geovana J Santos]**

* **LinkedIn:** [Geovana Santos](https://www.linkedin.com/in/geovana-jsantos/)
* **GitHub:** [Geoziihdev](https://github.com/Geoziihdev)

