<div class="article">
  <h2>Zoznam článkov</h2>
  <table id="table-home">
    <tr>
      <th width="300px"><strong>Názov článku</strong></th>
      <th width="200px"><strong>Rubrika</strong></th>
      <th width="70px"><strong>Autor</strong></th>
      <th width="100px"><strong>Publikovanie</strong></th>
      <?php if (strcmp($this->privileges, "admin") === 0 ) {?>
      <th width="70px"><strong>Zmena</strong></th>
      <th width="70px"><strong>Status</strong></th>
      <?php }?>
    </tr>
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
  </table>
</div>
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
