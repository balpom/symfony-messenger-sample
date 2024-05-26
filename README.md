# symfony-messenger-sample
## A simple example showing how to initialize and to use a Symfony Messenger with queues.

Not finding a simple and complete example of using the [Symfony Messenger](https://github.com/symfony/messenger/), understandable even to a dummies, I had to spend several days and create it myself.
It use Doctrine with sqlite database as Message Bus transport.
For Workers running uses [Symfony Console](https://github.com/symfony/console/).
Everything was tested in Linux.

### Requirements 
- **PHP >= 8.1**

### Installation
#### Using composer (recommended)
```bash
composer require balpom/symfony-messenger-sample
```

## How to use

### Simple test
Open console window. Run the command:
```bash
php bin/console messenger:consume doctrine-async
```
It starts simple Worker, which imitate SMS sending. Now it is waiting for messages to be sent from the queue, which is still empty.

Open another console window. Run the command:
```bash
php tests/send.php
```
It runs a simple script that adds several messages to the queue.
After this, in first console window you may see, how Worker "sending" SMS.

Run the command:
```bash
php bin/console messenger:stop-workers
```
It stop Worker execution.

### Advanced test
Open multiple consoles. In each of them, run the command:
```bash
php bin/console messenger:consume doctrine-async
```
It starts simple Worker, which imitate SMS sending. Now it is waiting for messages to be sent from the queue, which is still empty.

Open one more console window. Run the command:
```bash
php tests/sendmany.php
```
It runs a simple script that adds many several messages to the queue.
After this, in previously opened consoles you may see, how several Workers "sending" SMS.
Run the command:
```bash
php bin/console messenger:stop-workers
```
It stop all Workers executions.
