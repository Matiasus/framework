<!-- [START] Content -->
<div class="content">
<div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a> / <a href="/<?= $this->dir;?>">Články</a> / <a href="/<?= $this->subdir ?>"><?= $this->article->Category ?></a> / <a href="/<?= $this->link;?>"><?= $this->article->Title ?></a></div>
  <h2><?= $this->article->Title; ?></h2>
    <span class="date">Publikované <span style="color: #777;"><?= $this->article->Registered ?></span> v rubrike
      <a href="/<?= $this->subdir; ?>/"><?= $this->article->Category ?></a>
    </span>
  <div class="content"><?= $this->article->Content ?></div>
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
    <li><strong>Modify</strong>
    <ul>
      <li><a href="/<?= $this->privileges;?>/articles/add/">Pridaj článok</a></li>
      <li><a href="/<?= $this->privileges;?>/articles/edit/<?= $this->article->Title_unaccent ?>/<?= $this->article->Id ?>/">Edituj článok</a></li>
      <li><a href="/<?= $this->privileges;?>/articles/remove/<?= $this->article->Title_unaccent ?>/<?= $this->article->Id ?>/">Vymaž článok</a></li>
    </ul>
    </li>
  </ul>
</div>
<!-- [END] Menu -->
