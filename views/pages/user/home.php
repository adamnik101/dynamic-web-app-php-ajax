<?php
if(!isset($_SESSION['user'])):?>
<?php
        $randomArtists = executeQueryAll('select a.name as name, a.cover as cover, a.cover_small as coverSmall from artist a left join track_artist ta on a.id = ta.artist_id where ta.owner = 1 group by a.id order by rand() limit 4');
        $randomAlbums = executeQueryAll('select a.title as title, a.cover as cover, a.cover_small as coverSmall from album a order by rand() limit 4');
?>
    <div class="homepage">
        <div class="home-info">
            <h1>Waveocity</h1>
            <p class="p1">Discover new music every day.</p>
            <p>Start listening to the best new releases now. <a href="index.php?page=register">Register</a> or <a href="index.php?page=login">login</a> now!</p>
        </div>
        <div class="artist-home">
            <?php foreach ($randomArtists as $artist):?>
            <div class="artist">
                <img src="<?=$artist->cover?>" alt="<?=$artist->name?>">
                <p><?=$artist->name?></p>
            </div>
            <?php endforeach;?>
        </div>
        <div class="more">
            <p>And many more artists!</p>
            <h2>Listen to your favorite albums!</h2>
            <div class="albums-home">
                <?php foreach ($randomAlbums as $album):?>
                    <div class="album">
                        <img src="<?=$album->cover?>" alt="<?=$album->title?>">">
                        <p><?=$album->title?></p>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
<?php else: header('Location: index.php?page=streamplayer');die(); endif;