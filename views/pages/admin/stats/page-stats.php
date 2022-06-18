<?php
    $file = file(LOG);
    $userLog = file(LOGIN_LOG);
    $totalVisits = 0;
    $allPages = [];
    $totalVisits24 = 0;
    DEFINE("GENERATE", 'generate');

    $pagesDetails = array();

    include_once 'page.php';
    include_once 'user_log.php';

try {
    foreach ($file as $key=>$r){
        $values = explode(SEPARATOR, $r);
        $generatedPage = explode('/',$values[0])[3];
        $date = trim($values[2]);
        if($generatedPage != null){
            $page = explode(GENERATE,explode('.', $generatedPage)[0])[1];
            $toLower = explode('page',ucfirst(strtolower($page)))[0];

            if($toLower == ''){
                continue;
            }
            if(!in_array($toLower, $allPages)){
                array_push($allPages, $toLower);
                foreach ($allPages as $key1=>$k){
                    if($k == $toLower){
                        $pagesDetails[$key1] = new page($toLower);
                        if((time() - fullDate($date)) <= 86400){
                            $pagesDetails[$key1]->increaseVisits24(1);
                            $totalVisits24++;
                        }
                        $totalVisits++;
                        $pagesDetails[$key1]->increaseVisitsAllTime(1);
                    }


                }
            }
            elseif(in_array($toLower, $allPages)){
                foreach ($allPages as $key1=>$k){
                    if($k == $toLower){
                        if((time() - fullDate($date)) <= 86400){
                            $pagesDetails[$key1]->increaseVisits24(1);
                            $totalVisits24++;
                        }
                        $pagesDetails[$key1]->increaseVisitsAllTime(1);
                        $totalVisits++;
                    }

                }
            }
        }
        else{
            $normalPage = ucfirst($values[1]);
            if(!in_array($normalPage, $allPages)){
                array_push($allPages, $normalPage);
                foreach ($allPages as $key1=>$k){
                    if($k == $normalPage){
                        $pagesDetails[$key1] = new page($normalPage);
                        if((time() - fullDate($date)) <= 86400){
                            $pagesDetails[$key1]->increaseVisits24(1);
                            $totalVisits24++;
                        }
                        $pagesDetails[$key1]->increaseVisitsAllTime(1);
                        $totalVisits++;
                    }
                }
            }
            elseif(in_array($normalPage, $allPages)){
                foreach ($allPages as $key1=>$k){
                    if($k == $normalPage){
                        if((time() - fullDate($date)) <= 86400){
                            $pagesDetails[$key1]->increaseVisits24(1);
                            $totalVisits24++;
                        }
                        $pagesDetails[$key1]->increaseVisitsAllTime(1);
                        $totalVisits++;
                    }
                }
            }
        }
    }
}
catch (Error $exception){
        echo 'Exception: '.$exception->getMessage();
}

try {
        $usersLogged = array();
        $uniqueUsers = [];

    foreach ($userLog as $user){
        $row = explode(SEPARATOR,$user);
        $userId = $row[0];
        $firstName = $row[1];
        $lastName = $row[2];
        $mail = $row[3];
        $date = fullDate(trim($row[4]));
        if(!in_array($userId,$uniqueUsers )){
            if((time() - $date) <= 86400){
                array_push($uniqueUsers, $userId);
                $usersLogged[] = new user_log($userId, $firstName ,$lastName , $mail);
            }
        }
    }
}
catch (Error $exception){
        echo 'Error: '.$exception->getMessage();
        die();
}
$counter = 1;
$counterUser = 1;
?>
<?php if(count($usersLogged) > 0):?>
<h4>Users logged in last 24h: </h4>
<div class="table user-table">
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Full name</th>
            <th>Mail</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($usersLogged as $user):?>
            <tr>
                <td><?= $counterUser++?></td>
                <td><?= $user->GetFirstName().' '.$user->GetLastName()?></td>
                <td><?= $user->GetMail()?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php else:?>
<h4>No users logged in the last 24 hours.</h4>

<?php endif;?>
<h4>Page stats: </h4>
<div class="table">
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Visits 24h</th>
            <th>Percentage of 24h</th>
            <th>Total visits</th>
            <th>Percentage of total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pagesDetails as $page):?>
            <tr>
                <td><?= $counter++?></td>
                <td><?= $page->GetName()?></td>
                <td><?= $page->GetVisits24()?></td>
                <td><?= round(($page->GetVisits24() * 100) / ($totalVisits24), 2)?> %</td>
                <td><?= $page->GetVisitsAllTime()?></td>
                <td><?= round(($page->GetVisitsAllTime() * 100) / ($totalVisits), 2)?> %</td>
                </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

