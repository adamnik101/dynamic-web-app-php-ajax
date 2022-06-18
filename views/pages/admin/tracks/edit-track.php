<?php
    if(!isset($_GET['section']) || empty($_GET['section']) || !isset($_GET['trackId']) || empty($_GET['trackId']) || !isset($_SESSION['user']) || $_SESSION['user']->role != 'admin'){
        http_response_code(404);
        die();
    }
    include_once 'models/admin/tracks/functions.php';
    $id = $_GET['trackId'];
    $track = getTrack($id);
    ?>
<h4>Manage track:</h4>
    <div class="edit-track">
        <form method="post" action="models/admin/tracks/edit-track.php" enctype="multipart/form-data">
            <?php foreach ($track['track'] as $t):?>
                <label for="title">
                    Title of the track:
                    <input type="text" value="<?= $t->title?>" name="title">
                </label>
                <label for="path">
                    New file:
                    <input type="file" accept="audio/mp3" name="file">
                    <input type="text" hidden name="id" value="<?= $track['track'][0]->id?>">
                </label>
            <?php endforeach;?>
            <label for="artist-owner">
                Owner of the track is selected:
                <select name="owner">
                    <?php foreach ($track['artists'] as $artist):?>
                        <option value="<?= $artist->id?>" <?php
                            foreach ($track['track'] as $item){
                                if($item->artist == $artist->id){
                                    echo 'selected';
                                }
                            }?>><?= $artist->name?></option>
                    <?php endforeach;?>
                </select>
            </label>
            <label for="artist-features">
                Featuring:
                <select multiple name="features[]">
                    <?php foreach ($track['artists'] as $artist):?>
                        <option value="<?= $artist->id?>" <?php
                            foreach ($track['features'] as $feature){
                                if($feature->id == $artist->id){
                                    echo 'selected';
                                }
                            }

                        ?>><?= $artist->name?></option>
                    <?php endforeach;?>
                </select>
            </label>
            <label for="album">
                Album:
                <select name="album">
                    <?php foreach ($track['albums'] as $album):?>
                        <option value="<?= $album->albumId?>" <?php
                        foreach ($track['track'] as $item){
                            if($item->albumId == $album->albumId){
                                echo 'selected';
                            }
                        }?>><?= $album->album?></option>
                    <?php endforeach;?>
                </select>
            </label>
            <label for="submit">
                <input class="finish" type="submit" name="submit" value="Finish">

            </label>
        </form>
        <?php if(isset($_SESSION['message'])):?>
            <div class="message">
                <p><?= $_SESSION['message']?></p>
            </div>
            <?php unset($_SESSION['message']);?>
        <?php endif;?>
        <?php if(isset($_SESSION['error'])):?>
            <div class="errors">
                <p><?= $_SESSION['error']?></p>
            </div>
            <?php unset($_SESSION['error']);?>
        <?php endif;?>
    </div>
