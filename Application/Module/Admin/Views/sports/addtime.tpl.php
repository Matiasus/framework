<div class="content">
  <div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a> / <a href="/<?= $this->dir;?>"><?= $this->category ?></a></div>
  <div style="margin-right: 33%; margin:10px; margin-bottom: 20px;">
    <h2>Pridaj časy</h2>
    {formular addtime}
  </div>
</div>
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
      <li><a href="/<?= $this->privileges;?>/sports/addtime/">Pridaj čas</a></li>
    </ul>
    </li>
  </ul>
</div>
<!-- [Koniec] Bocne menu -->
