<div id="FullScreenOverlay" class="overlay">
    <span class="closebtn" onclick="closeSearchHero()" title="Close Overlay">×</span>
    <div class="overlay-content">
        <form role="search" method="get" id="search-form-mobile"  action="<?php echo home_url( '/' ); ?>">
            <input type="text" placeholder="Search for products, markets, or media" name="s">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
</div>
<div class="SearchHero text-center">
    <button class="openBtn" onclick="openSearchHero()"><i class="fa fa-search"></i></button>
</div>
<script>
    function openSearchHero() {
        document.getElementById("FullScreenOverlay").style.display = "block";
    }
    function closeSearchHero() {
        document.getElementById("FullScreenOverlay").style.display = "none";
    }
</script>