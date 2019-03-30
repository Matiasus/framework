<div class="content">
<div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a> / <a href="/<?= $this->dir;?>"><?= $this->category ?></a></div>
  <h2><?= $this->category ?></h2>
    <table id="id-components">
    <tr>
      <th style="width: 200px !important;"><strong>Šport</strong></th>
      <th><strong>Užívateľ</strong></th>
      <th><strong>Vzdialenosť [m]</strong></th>
      <th><strong>Čas</strong></th>
      <th><strong>Dátum</strong></th>
      <th><strong>Čas / km</strong></th>
    </tr>
    <?php 
    foreach ($this->runs as $run){ ?>
    <tr>
      <td>
        <?= $run->category;?>
      </td>
      <td>
        <?= $run->username;?>
      </td>
      <td>
        <?= $run->length;?>
      </td>
      <td>
        <?= $run->elapsed;?>
      </td>
      <td>
        <?= $run->registered;?>
      </td>
      <td>
        <?= $run->time_per_km;?>        
      </td>
    </tr>
    <?php } ?>
  </table>
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
  </ul>
</div>
<!-- [Koniec] Bocne menu -->
