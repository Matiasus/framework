<!-- [Zaciatok] Obsah -->
<div class="content">
  <div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a> / <a href="/<?= $this->dir;?>">Články</a> / <a href="/<?= $this->subdir ?>"><?= $this->article->Category ?></a> / <a href="/<?= $this->link;?>"><?= $this->article->Title ?></a></div>
  <h2>Pridaj článok</h2>
  {formular edit}
</div>
<!-- [Koniec] Obsah -->
<!-- [Zaciatok] Bocne menu -->
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
    </ul>
    </li>
  </ul>
</div>
<!-- [Koniec] Bocne menu -->
