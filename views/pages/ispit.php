<form action="models/admin/unos.php" enctype="multipart/form-data" method="POST">
    <label>Naziv</label>
    <input type="file" name="slika[]" multiple>
    <input type="checkbox" name="primary[]" >

    <button type="submit" name="submit">Unesi novi<button>

    
</form> 
<?php 

    $meni = array('Home', "Contact", "About", "Message", "Get bitches");

?>

<div class="menu" style="width: 40%;">
    <ul style="display: flex; justify-content:space-between; ">
        <?php foreach($meni as $link):?>
            <li class="link" style="position: relative;">
            <ul class="ddl-hidden" style="display: none; position:absolute; left:0; top:50px;">
                <li>novi</li>
                <li>novi</li>
            </ul>
            <?= $link?></li>
            
        <?php endforeach;?>
    </ul>

</div>


<script>

let links = document.getElementsByClassName("link");

for(link of links){
    link.onclick = (e) => {
        showDDL(e.target);
    }
}


function showDDL(element){
    console.log(element.getElementsByClassName("ddl-hidden")[0].style.display)
    if(element.getElementsByClassName("ddl-hidden")[0].style.display == "block"){
        element.getElementsByClassName("ddl-hidden")[0].style.display = "none";
    }
    else{
        console.log(element)
        element.getElementsByClassName("ddl-hidden")[0].style.display = "block";
    }

}


</script>