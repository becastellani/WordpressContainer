# Wordpress Container with MySQL, Redis and Prometheus
<div align="center">
	<code><img width="50" src="https://user-images.githubusercontent.com/25181517/192158957-b1256181-356c-46a3-beb9-487af08a6266.png" alt="Wordpress" title="Wordpress"/></code>
	<code><img width="50" src="https://user-images.githubusercontent.com/25181517/183896128-ec99105a-ec1a-4d85-b08b-1aa1620b2046.png" alt="MySQL" title="MySQL"/></code>
	<code><img width="50" src="https://user-images.githubusercontent.com/25181517/182884894-d3fa6ee0-f2b4-4960-9961-64740f533f2a.png" alt="redis" title="redis"/></code>
	<code><img width="50" src="https://user-images.githubusercontent.com/25181517/117207330-263ba280-adf4-11eb-9b97-0ac5b40bc3be.png" alt="Docker" title="Docker"/></code>
	<code><img width="50" src="https://user-images.githubusercontent.com/25181517/182534182-c510199a-7a4d-4084-96e3-e3db2251bbce.png" alt="Prometheus" title="Prometheus"/></code>
</div>

---
## Estrutura do Projeto

O projeto é separado nas seguintes pastas:

![image](https://github.com/becastellani/WordpressContainer/assets/73252166/3ed162bb-0562-4e89-82ad-b7aad668a25f)

Foi criado uma pasta para o MySQL, pois contém uma query já pronta com dados fictícios para testes do mesmo, além disso, os plugins já estão pré configurados na pasta `./wp`.

Antes de iniciarmos o projeto, é necessário liberarmos algumas métricas do Docker para que o Prometheus possa fazer a leitura corretamente, para isso, edite o arquivo `daemon.json` do Docker:
- No Windows: `%programdata%\docker\config\daemon.json`
- No Linux: `/etc/docker/daemon.json`
- No macOS: `~/.docker/daemon.json`

**Após acessar, adicione a seguinte configuração**

    {
      "experimental": false,
      "metrics-addr": "127.0.0.1:9323"
    }

**Depois de adicionado, reinicie o Docker para aplicar as alterações**

**Para mais informações sobre a configuração do prometheus no Docker, você pode acessar:**
- `https://github.com/becastellani/WordpressContainer.git`

---

## Passo a Passo

1. **Clone o repositório**

- `https://github.com/becastellani/WordpressContainer.git`

2. **Execute o container**
- `docker compose up -d`

4. **Para acessar a página de login wordpress que já foi deixada configurada, acesse a URL:**
- `http://localhost:8000/wp-login.php`

5. **Faça o login com os seguintes dados:**

![image](https://github.com/becastellani/WordpressContainer/assets/73252166/3c8993a2-5b9f-4cd4-bee9-e9c90b004223)

5. **Após o login, temos a tela de ínicio do Wordpress, como os Plugins já vieram na instalação do Docker, podemos acessar diretamente eles, como o status do `Redis`**

![image](https://github.com/becastellani/WordpressContainer/assets/73252166/d105d23a-a718-480a-ac4e-971d8b326887)

6. **Como executamos uma query logo na inicialização do Container, é criado também uma página com uma Tabela mostrando os dados inseridos no `MySQL`**

`http://localhost:8000/?page_id=2`

![image](https://github.com/becastellani/WordpressContainer/assets/73252166/4968fedd-eed0-48cb-9c90-906a190a93e2)

7. **Monitoramento do Docker com Prometheus**
- Acesse a URL: `localhost:9090`
- Navegue para **Status > Targets**

![image](https://github.com/becastellani/WordpressContainer/assets/73252166/f9070932-c1ac-444e-ab5e-bdc0ab39fd70)

![image](https://github.com/becastellani/WordpressContainer/assets/73252166/6a4550b2-8d53-4e2f-ae61-dc5e1723fdb5)

---

## Conclusão
Parabéns! Agora você tem um ambiente completo com Wordpress, MySQL, Redis e Prometheus configurado em containers Docker

Se você tiver algum problema, sinta-se à vontade para abrir uma [issue](https://github.com/becastellani/WordpressContainer/issues) no repositório. Contribuições são bem-vindas!



