<div class="content">
  <div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a> / <a href="/<?= $this->dir;?>">Súčiastky</a></div>
  <h2>Zoznam súčiastok</h2>
    <table id="id-components">
    <thead>
    <tr>
      <th>Popis</th>
      <th>Hodnota</th>
      <th>Počet</th>
      <?php if (strcmp($this->privileges, "admin") === 0 ) {?>
      <th>Upraviť</th>
      <?php }?>
    </tr>
    </thead>
    <tbody>
    <?php 
    foreach ($this->components as $component){ ?>
    <tr>
      <td>
        <a href="/<?= $this->privileges, '/components/category/', $component->Category_unaccent, '/';?>">
        <?= $component->Category;?>
        </a>
      </td>
      <td>
        <a href="/<?= $this->privileges, '/components/detail/', $component->Category_unaccent, '/', $component->Description_unaccent, '/', $component->Id;?>/">
        <?= $component->Description;?>
        </a>
      </td>
      <td>
        <?= $component->Amount;?>
      </td>
      <?php if (strcmp($this->privileges, "admin") === 0 ) {?>
      <td>
        <a href="/<?= $this->privileges, '/components/edit/', $component->Category_unaccent, '/', $component->Description_unaccent, '/', $component->Id;?>/">Upraviť</a>
      </td>
      <?php }?>
    </tr>
    <?php } ?>
   </tbody>
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
    <li><strong>Modify</strong>
    <ul>
      <li><a href="/<?= $this->privileges;?>/home/add/">Pridaj</a></li>
    </ul>
    </li>
  </ul>
</div>
<!-- [Koniec] Bocne menu -->
