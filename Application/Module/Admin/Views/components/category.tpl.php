<div class="content">
  <div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a> / <a href="/<?= $this->dir;?>">Súčiastky</a></div>
  <h2>Zoznam kondenzátorov "<?= $this->component; ?>"</h2>
    <div id="outercontent">
      <table id="id-tablecontent">
      <thead>
      <tr>
        <th>Zosilňovač</th>
        <th>Typ</th>
        <th>Hodnota</th>
        <th>Označenie</th>
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
          <a href="/<?= $this->privileges, '/components/category/mark/', $component->mark_unaccent;?>/">
          <?= $component->mark;?>
          </a>
        </td>
        <td>
          <a href="/<?= $this->privileges, '/components/category/type/', $component->type_unaccent;?>/">
          <?= $component->type;?>
          </a> 
        </td>
        <td>
          <a href="/<?= $this->privileges, '/components/detail/', $component->category_unaccent, '/', $component->id;?>/">
          <?= $component->description;?>
          </a>
        </td>
        <td>
          <?= $component->label;?>
        </td>
        <td>
          <?= $component->amount;?>      
        </td>
        <?php if (strcmp($this->privileges, "admin") === 0 ) {?>
        <td>
          <a href="/<?= $this->privileges, '/components/edit/', $component->Category_unaccent, '/', $component->Description_unaccent, '/', $component->Id;?>/">Upraviť</a>
        </td>
        <?php }?>
      </tr>
      <?php } ?>
     </tbody>
    <div>
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
