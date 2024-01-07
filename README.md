# Envoice

<div align="center">

[![License](https://img.shields.io/badge/License-MIT-blue)](#license "Go to license section")

</div>

Application for invoicing and company budget management :moneybag:
The application enables issuing invoices, company budget management,
invoice management, charts, and other functionalities that facilitate
company management.

## Technologies

<div align="center">

[![Technologies used](https://skillicons.dev/icons?i=php,symfony,redis,mysql,rabbitmq,js,bootstrap)](https://skillicons.dev)

</div>

## Getting started
- Make sure you have Docker (Linux/MacOs) or Docker Desktop (Windows) and docker-compose installed
- Clone repository
- Open bash and type `docker-compose up --build` 
- That's all!

## Project dependency
### Symfony application | `localhost:8080/`

### Mailcatcher | `localhost:1080/`

### RabbitMQ | `localhost:15672/`

**Launching consumers**

`php bin/console messenger:consume queue_name`

eg.

`php bin/console messenger:consume async_email --time-limit=3600`

## License

Released under [MIT](/LICENSE) by [@Zevcore](https://github.com/Zevcore).

- You can freely modify and reuse.
- The _original license_ must be included with copies of this software.
- Please _link back_ to this repo if you use a significant portion the source code.