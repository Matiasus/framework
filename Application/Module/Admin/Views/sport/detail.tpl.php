<!-- [START] Content -->
<div class="content">
<div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a> / <a href="/<?= $this->dir;?>">Články</a> / <a href="/<?= $this->subdir;?>"><?= $this->article->category ?></a></div>
  <h2><?= $this->article->title; ?></h2>
    <span class="date">Publikované <span style="color: #777;"><?= $this->article->registered ?></span> v rubrike
      <a href="/<?= $this->subdir; ?>/"><?= $this->article->category ?></a>
    </span>
  <div class="content"><?= $this->article->content ?></div>
</div>
<!-- [END] Content -->

<!-- [START] Menu -->
<div id="menu">
  <h3>Menu</h3>
  <ul>
    <li><strong>Konto</strong>
    <ul>
      <li><a href="/<?= $this->privileges;?>/user/logoff/">Odhlas</a></li>
    </ul>
    </li>
  </ul>
</div>
<!-- [END] Menu -->
