<div class="content">
  <div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a> / <a href="/<?= $this->dir;?>">Články</a></div>
  <h2>Zoznam článkov</h2>
    <div id="outercontent">
      <table id="id-tablecontent">
      <thead>
      <tr>
        <th><strong>Názov článku</strong></th>
        <th><strong>Rubrika</strong></th>
        <th><strong>Autor</strong></th>
        <th><strong>Publikovanie</strong></th>
        <?php if (strcmp($this->privileges, "admin") === 0 ) {?>
        <th><strong>Zmena</strong></th>
        <th><strong>Status</strong></th>
        <?php }?>
      </tr>
      </thead>
      <tbody>
      <?php 
      foreach ($this->articles as $article){ ?>
      <tr>
        <td>
          <a href="/<?= $this->privileges, '/', $article->category_unaccent, '/detail/', $article->title_unaccent, '/', $article->id;?>/">
          <?= $article->title;?>
          </a>
        </td>
        <td>
          <a href="/<?= $this->privileges, '/', $article->category_unaccent, '/default/'?>">
          <?= $article->category;?>
          </a>
        </td>
        <td>
          <?= $article->Username;?>
        </td>
        <td>
          <?= $article->registered;?>
        </td>
        <?php if (strcmp($this->privileges, "admin") === 0 ) {?>
        <td>
          <a href="/<?= $this->privileges, '/articles/edit/', $article->title_unaccent, '/', $article->id;?>/">Upraviť</a>
        </td>
        <td>
          <a href="/<?= $this->privileges, '/articles/default/', $article->type, '/', $article->id, '/?do=status';?>"><?= $article->type; ?></a>
        </td>
        <?php }?>
      </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<!-- [Zaciatok] Bocne menu -->
<div id="menu">
  <h3>Menu</h3>
  <ul>
    <li><strong>Proces</strong>
    <ul>
      <li><a href="/<?= $this->privileges;?>/articles/add/">Pridaj</a></li>
    </ul>
    </li>
    <li><strong>Konto</strong>
    <ul>
      <li><a href="/<?= $this->privileges;?>/user/logoff/">Odhlas</a></li>
    </ul>
    </li>
  </ul>
</div>
<!-- [Koniec] Bocne menu -->
