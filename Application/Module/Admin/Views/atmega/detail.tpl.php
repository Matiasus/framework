<!-- [START] Content -->
<div class="article">
	<h2><?= $this->article->title; ?></h2>
		<span class="date">Publikované <span style="color: #777;"><?= $this->article->registered ?></span> v rubrike
      <a href="/<?= $this->privileges;?>/<?= $this->article->category_unaccent;?>/default/"><?= $this->article->category ?></a>
		</span>
	<div class="content"><?= $this->article->content ?></div>
</div>
<!-- [END] Content -->

<!-- [START] Menu -->
<div id="menu">
	<h3>Menu</h3>
</div>
<!-- [END] Menu -->
