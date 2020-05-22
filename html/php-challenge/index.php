<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	// ログインしている
	$_SESSION['time'] = time();

	$members = $db->prepare('SELECT * FROM members WHERE id=?');
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
} else {
	// ログインしていない
	header('Location: login.php');
	exit();
}

// 投稿を記録する
if (!empty($_POST)) {
	if ($_POST['message'] != '') {
		$message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_post_id=?, created=NOW()');
		$message->execute(array(
			$member['id'],
			$_POST['message'],
			$_POST['reply_post_id']
		));

		header('Location: index.php');
		exit();
	}
}

// 投稿を取得する
$page = $_REQUEST['page'];
if ($page == '') {
	$page = 1;
}
$page = max($page, 1);

// 最終ページを取得する
$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);

$start = ($page - 1) * 5;
$start = max(0, $start);

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?, 5');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

// 返信の場合
if (isset($_REQUEST['res'])) {
	$response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
	$response->execute(array($_REQUEST['res']));

	$table = $response->fetch();
	$message = '@' . $table['name'] . ' ' . $table['message'];
}

// htmlspecialcharsのショートカット
function h($value)
{
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// 本文内のURLにリンクを設定します
function makeLink($value)
{
	return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>', $value);
}


// ================== ここから「いいね！」 ===================
//まずログインユーザーがいいね！済みかどうか確認（いいねが１件以上の場合重複登録不可）
if (isset($_REQUEST['likes'])) {
	$checkOfLikes = $db->prepare('SELECT COUNT(*) AS cnt FROM likes WHERE member_id=? AND posts_id=?');
	$checkOfLikes->execute(array(
		$member['id'],
		$_REQUEST['likes']
	));
	$checkResult = $checkOfLikes->fetch();
	//ここからいいね！の動作の定義
	if ($checkResult['cnt'] > 0) {
		//いいね済みならDELETE
		$delete = $db->prepare('DELETE FROM likes WHERE member_id=? AND posts_id=?');
		$delete->execute(array(
			$member['id'],
			$_REQUEST['likes']
		));
	} else {
		//まだいいねしてないならINSERT
		$like = $db->prepare('INSERT INTO likes SET member_id=?, posts_id=?, created=NOW()');
		$like->execute(array(
			$member['id'],
			$_REQUEST['likes']
		));
	}
}
// ================== /「いいね！」ここまで ===================


// =================== ここからリツイート ====================
//ログインユーザーがリツイート済みか確認
if (isset($_REQUEST['retweet'])) {
	$checkOfRetweet = $db->prepare('SELECT COUNT(*) AS cnt FROM posts WHERE member_id=? AND retweet_id=?');
	$checkOfRetweet->execute(array(
		$member['id'],
		$_REQUEST['retweet']
	));
	$checkResults = $checkOfRetweet->fetch();

	//リツイートされる投稿をSELECT,retweet_postを作る
	$retweet = $db->prepare('SELECT m.name, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
	$retweet->execute(array($_REQUEST['retweet']));
	$retweetArea = $retweet->fetch();
	$retweet_post = '@' . $member['name'] . 'さんがリツイート' . ' ' . $retweetArea['message'] . '(' . $retweetArea['name'] . ')';

	//リツイート済みであれば-1、なければ+1
	if ($checkResults['cnt'] > 0) {
		//リツイート済みなら削除
		$delete = $db->prepare('DELETE FROM posts WHERE member_id=? AND retweet_id=?');
		$delete->execute(array(
			$member['id'],
			$_REQUEST['retweet']
		));
		header('Location: index.php');
		exit();
	} else {
		//まだリツイートしていないならリツイート
		$addRetweet = $db->prepare('INSERT INTO posts SET message=?, member_id=?,retweet_id=?, created=NOW()');
		$addRetweet->execute(array(
			$retweet_post,
			$member['id'],
			$_REQUEST['retweet']
		));
		header('Location: index.php');
		exit();
	}
}
// =================== /リツイートここまで ====================
?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>
	<link rel="stylesheet" href="style-sh.css">
	<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>ひとこと掲示板</h1>
		</div>
		<div id="content">
			<div style="text-align: right"><a href="logout.php">ログアウト</a></div>
			<form action="" method="post">
				<dl>
					<dt><?php print h($member['name']); ?>さん、メッセージをどうぞ</dt>
					<dd>
						<textarea name="message" cols="50" rows="5"><?php print h($message); ?></textarea>
						<input type="hidden" name="reply_post_id" value="<?php print h($_REQUEST['res']); ?>" />
					</dd>
				</dl>
				<div>
					<p>
						<input type="submit" value="投稿する" />
					</p>
				</div>
			</form>

			<?php foreach ($posts as $post) : ?>
				<div class="msg">
					<img class="member_picture" src="member_picture/<?php print h($post['picture']); ?>" width="48" height="48" alt="" />
					<p>
						<?php print makeLink(h($post['message'])); ?>
						<span class="name">（<?php print h($post['name']); ?>）</span>[<a href="index.php?res=<?php print h($post_id); ?>">Re</a>]
					</p>
					<p class="day">
						<a href="view.php?id=<?php print h($post_id); ?>"><?php print h($post['created']); ?></a>
						<?php if ($post['reply_message_id'] > 0) : ?>
							<a href="view.php?id=<?php print h($post['reply_message_id']); ?>">
								返信元のメッセージ</a>
						<?php endif; ?>
						<?php if ($_SESSION['id'] === $post['member_id']) : ?>
							[<a href="delete.php?id=<?php print h($post['id']); ?>" style="color: #F33;">削除</a>]
						<?php endif; ?>
						<!---- ===========「いいね！」ここから ============= ---->
						<!-- ログインユーザーがいいね！済みかどうか確認 -->
						<!-- この辺も上にまとめて書けるね？ -->
						<?php
						//いいね！する投稿がリツイートされていれば、リツイート元のpostIDを利用する
						if ($post['retweet_id'] > 0) {
							$post_id = $post['retweet_id'];
						} else {
							$post_id = $post['id'];
						}
						?>
						<!-- いいね！済みであればいいね-1、まだならいいね！+1をcssで分かりやすく表示するための条件分岐 -->
						<?php if ($checkResult['cnt'] > 0) : ?>
							<!-- 既にいいね済→→削除 -->
							<a href="index.php?likes=<?php print h($post_id); ?>"><span class="fa fa-heart like"></span></a>
						<?php else : ?>
							<!-- 未いいね→→追加 -->
							<a href="index.php?likes=<?php print h($post_id); ?>"><span class="fa fa-heart unlike"></span></a>
						<?php endif; ?>
						<!-- いいね！の件数出力 -->
						<!-- ここからいいね数の集計 -->
						<?php
						//いいね数の集計処理
						$like_counts = $db->prepare('SELECT COUNT(*) as cnt FROM likes WHERE posts_id=?');
						$like_counts->execute(array($post['id']));
						$like_count = $like_counts->fetch();
						print h($like_count['cnt']);
						?>

						<!-- ============ /いいね！ここまで ============ -->

						<!-- ============ リツイートここから =========== -->
						<!-- ログインユーザーがリツイート済みか確認 -->
						<?php
						$checkOfRetweet = $db->prepare('SELECT COUNT(*) AS cnt FROM posts WHERE member_id=? AND retweet_id=?');
						$checkOfRetweet->execute(array(
							$member['id'],
							$post_id
						));
						$retweet = $checkOfRetweet->fetch();
						?>
						<!-- リツイート済みであれば-1、なければ+1を表示 -->
						<?php if ($retweet['cnt'] > 0) : ?><a href="index.php?retweet=<?php print h($post_id); ?>"><i class="fas fa-retweet retweet"></i></a>
						<?php else : ?>
							<a href="index.php?retweet=<?php print h($post_id); ?>"><i class="fas fa-retweet unretweet"></i></a>
						<?php endif; ?>
						<!-- リツイートの件数を取得し、出力 -->
						<?php
						$retweet_counts = $db->prepare('SELECT COUNT(*) as cnt FROM posts WHERE retweet_id=?');
						$retweet_counts->execute(array($post_id));
						$retweet_count = $retweet_counts->fetch();
						print h($retweet_count['cnt']);
						?>
						<!---- ========== /リツイートここまで ========== ---->
					</p>
				</div>
			<?php endforeach; ?>

			<ul class="paging">
				<?php if ($page >= 2) : ?>
					<li><a href="index.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
				<?php endif; ?>
				<?php if ($page < $maxPage) : ?>
					<li><a href="index.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</body>

</html>