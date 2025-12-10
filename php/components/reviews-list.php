<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Idioma por defecto español
}

$langFile = dirname(__DIR__, 1) . '/../language/lang_' . $_SESSION['lang'] . '.php';
if (file_exists($langFile)) {
    include $langFile;
} else {
    include dirname(__DIR__, 1) . '/../language/lang_es.php';
}

$html_lang = $_SESSION['lang'];

require_once __DIR__ . '/../config/db.php';

$mysqli = openConnection();

$sqlReviews = "SELECT * FROM feedback WHERE status = 'Aprobada';";

$reviewsAprobadas = $mysqli->prepare($sqlReviews);
$reviewsAprobadas->execute();
$reviewsResult = $reviewsAprobadas->get_result();

closeConnection($mysqli);

require_once __DIR__ . '/../helpers/get-score.php';

?>
<article class="resenas-container">
    <?php 
    if ($reviewsResult) {
        while ($filaReview = $reviewsResult->fetch_assoc()):
    ?>
    <div class="resena">
        <div class="icono">👤</div>
        <div class="text-start">
        <div class="fw-bold">
            <?php 
            echo $filaReview['full_name']; 
            echo getScore($filaReview['score']);
            ?> 
        </div>
        <p><?php echo $filaReview['comment'];?></p>
        </div>
    </div>
<?php 
        endwhile;
    }

?>
</article>
