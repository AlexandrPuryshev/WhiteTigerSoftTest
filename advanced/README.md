Чтобы запустить этот проект нужно сначала выполнить такие команды (по порядку):
 1) composer global require "fxp/composer-asset-plugin:^1.2.0"
 2) composer update или composer install
 3) php init
 4) следовать инструкциям php init
 5) зайти в папку по адресу (папка проекта)\common\config
 6) открыть файл main-local, сам файл должен содрежать следующее( пример):
          <?php
            return [
                'components' => [
                    'db' => [
                        'class' => 'yii\db\Connection',
                        'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
                        'username' => 'root',
                        'password' => '',
                        'charset' => 'utf8',
                        'tablePrefix' => 'social_',
                    ],
                    'mailer' => [
                        'class' => 'yii\swiftmailer\Mailer',
                        'viewPath' => '@common/mail',
                        // send all mails to a file by default. You have to set
                        // 'useFileTransport' to false and configure a transport
                        // for the mailer to send real emails.
                        'useFileTransport' => true,
                    ],
                ],
            ];
7) выполнить команду php yii migrate         
