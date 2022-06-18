<?php
    if(!isset($_SESSION['user'])){
        header('Location: index.php?page=homepage'); // redirect za ne ulogovanog korisnika
        die();
    }
    elseif($_SESSION['user']->role != 'admin'){ // redirect za ulogovanog
            header('Location: index.php?page=home');
            die();
    }

    include_once 'navigation/navigation.php';
    ?>
<div class="admin">
    <div class="top">
        <div class="img-admin">
            <i class="fas fa-user-cog"></i>
        </div>
        <h1>Hello <span><?= $_SESSION['user']->firstName?></span>, Welcome to admin panel!</h1>
    </div>
    <div class="bottom">
            <?php if(isset($_GET['section'])){
                switch ($_GET['section']){
                    case 'stats' : {
                        include_once 'stats/page-stats.php';
                    }break;
                    case 'users' : {
                        include_once 'users/users.php';
                    }break;
                    case 'editUser' : {
                        include_once 'users/edit-user.php';
                    }break;
                    case 'tracks' : {
                        include_once 'tracks/tracks.php';
                    }break;
                    case 'editTrack' : {
                        include_once 'tracks/edit-track.php';
                    }break;
                    case 'addTrack' : {
                        include_once 'tracks/add-track.php';
                    }break;
                    case 'artists' : {
                        include_once 'artists/artists.php';
                    }break;
                    case 'addArtist' : {
                        include_once 'artists/add-artist.php';
                    }break;
                    case 'editArtist' : {
                        include_once 'artists/edit-artist.php';
                    }break;
                    case 'albums' : {
                        include_once 'albums/albums.php';
                    }break;
                    case 'addAlbum' : {
                        include_once 'albums/add-album.php';
                    }break;
                    case 'editAlbums' : {
                        include_once 'albums/edit-album.php';
                    }break;
                    case 'genres' : {
                        include_once 'genres/genres.php';
                    }break;
                    case 'addGenre' : {
                        include_once 'genres/add-genre.php';
                    }break;
                    case 'editGenre' : {
                        include_once 'genres/edit-genre.php';
                    }break;
                    case 'playlists' : {
                        include_once 'playlists/playlists.php';
                    }break;
                    default : {
                        include_once 'stats/page-stats.php';
                    }
                }
            }
            else{
                include_once 'stats/page-stats.php';
            }
            ?>
    </div>

</div>
