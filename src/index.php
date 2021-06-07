<?php
require('./db_setting.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_start();
    if (isset($_SESSION["key"], $_POST["key"]) && $_SESSION["key"] == $_POST["key"]) {
        unset($_SESSION["key"]);
        post_message();
    }
}

session_start();
$_SESSION["key"] = md5(uniqid().mt_rand());

function get_messages()
{
    $pdo = create_pdo();
    try {
        $stmt = $pdo->prepare("SELECT * FROM message_board");
        $stmt->execute();
        $messages = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log($e);
    }
    return $messages;
}

function post_message()
{
    if (!isset($_POST["user_name"]) &&
        !isset($_POST["message"])
    ) {
        return;
    }
    $pdo = create_pdo();
    try {
        $query = "INSERT INTO message_board(user_name, message) VALUES (:user_name, :message)";
        $stmt = $pdo->prepare($query);
        $data = [
            ":user_name" => $_POST["user_name"],
            ":message" => $_POST["message"]
        ];
        $stmt->execute($data);
    } catch (Exception $e) {
        error_log($e);
    }
}

$messages = get_messages();
?>
<!doctype html>
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="/css/normalize.css" />
        <link rel="stylesheet" href="/css/style.css" />
    </head>
    <body>
        <header>
            <h1>Tohu ENV</h1>
            <p>Tohu ENVはApache+PHP+MySQLの環境のサンプルです。</p>
        </header>
        <main>
            <section>
                <h1>How to use</h1>
                <ol>
                    <li>
                        <code>docker-compose up</code>でコンテナを立ち上げ
                    </li>
                    <li>
                        後はsrcディレクトリをお好みで
                    </li>
                </ol>
            </section>
            <section>
                <h1>コンテナ環境</h1>
                <h2>php:7.4-apache</h2>
                    <p>docker/apache/conf/httpd.conf -> /usr/local/apache2/conf/</p>
                    <p>docker/apache/conf/php.ini -> /usr/local/etc/php/</p>
                    <p>src <-> /var/www/html</p>
                <h2>mysql:5.7</h2>
                    <p>docker/db/conf/my.cnf -> /etc/mysql/conf.d/</p>
                    <p>docker/db/init -> /docer-entrypoint-initdb.d</p>
            </section>
            <div class="divider"></div>
            <form method="POST" action="/">
                <input type="hidden" name="key" value="<?php echo htmlspecialchars($_SESSION["key"], ENT_QUOTES); ?>" />
                <label>お名前：<input type="text" name="user_name" required /></label>
                <label>メッセージ:</label>
                <textarea width="800" height="600" name="message" required ></textarea>
                <div class="submit">
                    <input type="submit" value="送信" />
                </div>
            </form>
            <div class="divider"><span class="divider_message">メッセージ</span></div>
            <div class="messages">
            <?php
            foreach ($messages as $message) {
            ?><p class="message">
                <span class="user_name"><?php echo $message["user_name"];?></span>
                 :<span class="message"><?php echo $message["message"]; ?></span>
              </p>
            <?php
            }
            ?>
            </div>
            
        </main>
        <footer>
            <p>Author : シロー</p>
            <a href="https://shiro-secret-base.com">ブログ(https://shiro-secret-base.com)</a>
        </footer>
    </body>
</html>
