<div class="content">
  <div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a></div>
  <h2>Prehľad</h2>
    <table id="id-components">
    <thead>
    <tr>
      <th>Rubrika</th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>
        <a href="/<?= $this->privileges, '/sports/default/';?>">Beh</a>
      </td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>
        <a href="/<?= $this->privileges, '/articles/default/';?>">Články</a>
      </td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>
        <a href="/<?= $this->privileges, '/components/default/';?>">Súčiastky</a>
      </td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
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
