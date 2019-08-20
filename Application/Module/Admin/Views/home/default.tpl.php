<div class="content">
  <div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a></div>
  <h2>Prehľad</h2>
    <div id="outercontent">
      <table class="tablecontent default">
      <thead>
      <tr>
        <th>Rubrika</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>
          <a href="/<?= $this->privileges, '/sports/default/';?>">Beh</a>
        </td>
      </tr>
      <tr>
        <td>
          <a href="/<?= $this->privileges, '/articles/default/';?>">Články</a>
        </td>
      </tr>
      <tr>
        <td>
          <a href="/<?= $this->privileges, '/components/default/';?>">Súčiastky</a>
        </td>
      </tr>
     </tbody>
    </table>
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
  </ul>
</div>
<!-- [Koniec] Bocne menu -->
