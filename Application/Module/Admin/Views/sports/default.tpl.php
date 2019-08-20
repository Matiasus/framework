<div class="content">
  <div id="navigationTop"><a href="/<?= $this->root;?>">Domov</a> / <a href="/<?= $this->dir;?>"><?= $this->category ?></a></div>
  <h2><?= $this->category ?></h2>
    <div id="outercontent">
      <table class="tablecontent run">
      <thead>
      <tr>
        <th style="width: 200px !important;"><strong>Šport</strong></th>
        <th><strong>Dátum</strong></th>
        <th><strong>Dĺžka [m]</strong></th>
        <th><strong>Čas</strong></th>
        <th><strong>Čas / km</strong></th>
        <th><strong>Pulz</strong></th>
        <th><strong>Level</strong></th>
        <th><strong>Pokles</strong></th>
        <th><strong>Kalórie</strong></th> 
        <th><strong>Tuk</strong></th>               
      </tr>
      </thead>
      <tbody>
      <?php 
      foreach ($this->runs as $run){ ?>
      <tr>
        <td>
          <?= $run->Category;?>
        </td>
        <td>
          <?= $run->Registered;?>
        </td>
        <td>
          <a href="/<?= $this->privileges, '/sports/detail/length/', $run->Length;?>/">
          <?= $run->Length;?>
          </a>
        </td>
        <td>
          <?= $run->Elapsed;?>
        </td>        
        <td>
          <?= $run->Time_per_km;?>
        </td>      
        <td>
          <?= $run->Pulse_avg;?>        
        </td>
        <td>
          <?= $run->Fitness_level;?>        
        </td>  
        <td>
          <?= $run->Fitness_pulse;?>        
        </td>
        <td>
          <?= $run->Calories;?>
        </td>
        <td>
          <?= $run->Fat;?>
        </td>
      </tr>
      <?php } ?>
      </tbody>
    </div>
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
      <li><a href="/<?= $this->privileges;?>/sports/addtime/">Pridaj čas</a></li>
    </ul>
    </li>
  </ul>
</div>
<!-- [Koniec] Bocne menu -->
