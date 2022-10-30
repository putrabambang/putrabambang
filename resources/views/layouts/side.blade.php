<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
          <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil" alt="User Image">
      </div>
      <div class="pull-left info">
      <span>Selamat Datang</span>
          <p>{{ auth()->user()->name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
  </div>
  
  <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
  
        <li>
          <a href="{{route('dashboard')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        @if (auth()->user()->level == 1)
        <li class="header">MASTER</li> 
        <li>
          <a href="{{route('kategori.index')}}">
            <i class="fa fa-cube"></i> <span>Kategori</span>
          </a>
        </li>
        <li>
          <a href="{{route('barang.index')}}">
            <i class="fa fa-cubes"></i> <span>Barang</span>
          </a>
        </li>
        <li>
          <a href="{{route('member.index')}}">
            <i class="fa fa-id-card"></i> <span>Member</span>
          </a>
        </li>
        <li>
          <a href="{{route('item.index')}}">
            <i class="fa fa-cubes"></i> <span>Item Penggilingan</span>
          </a>
        </li>
        <li class="header">TRANSAKSI TOKO</li> 
        <li>
          <a href="{{ route('penjualan.index') }}">
              <i class="fa fa-upload"></i> <span>Data Penjualan</span>
          </a>
      </li>
        <li>
          <a href="{{route('transaksi.baru')}}">
            <i class="fa fa-cart-arrow-down"></i> <span>Transaksi baru</span>
          </a>
        </li>
        <li>
          <a href="{{route('transaksi.index')}}">
            <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Aktif</span>
          </a>
        </li>
        <li>
          <a href="{{route('pengeluaran.index')}}">
            <i class="fa fa-money" aria-hidden="true"></i> <span>Pengeluaran Toko</span>
          </a>
        </li>
        <li class="header">TRANSAKSI BAKSO</li> 
        <li>
          <a href="{{route('penggilingan.index')}}">
              <i class="fa fa-upload"></i> <span>Data Penggilingan</span>
          </a>
      </li>
        <li>
          <a href="{{route('order.baru')}}">
            <i class="fa fa-cart-arrow-down"></i> <span>Penggilingan baru</span>
          </a>
        </li>
        <li>
          <a href="{{route('order.index')}}">
            <i class="fa fa-cart-arrow-down"></i> <span>Penggilingan Aktif</span>
          </a>
        </li>
         <li>
            <a href="{{route('pengeluaranbakso.index')}}">
              <i class="fa fa-money" aria-hidden="true"></i> <span>Pengeluaran Gilingan</span>
            </a>
          </li>
        <li class="header">REPORT</li> 
        <li>
          <a href="{{route('laporan.index')}}">
            <i class="fa fa-book" aria-hidden="true"></i> <span>Laporan Pendapatan Toko</span>
          </a>
        </li>
        <li>
          <a href="{{route('laporanbarang.index')}}">
            <i class="fa fa-book" aria-hidden="true"></i> <span>Laporan Barang terjual</span>
          </a>
        </li>
        <li>
          <a href="{{route('laporanbakso.index')}}">
            <i class="fa fa-book" aria-hidden="true"></i> <span>Laporan Pendapatan Gilingan</span>
          </a>
        </li>
        <li class="header">SYSTEM</li> 
        <li>
          <a href="{{route('user.index')}}">
            <i class="fa fa-users" aria-hidden="true"></i><span>User</span>
          </a>
        </li> <li>
          <a href="{{route('setting.index')}}">
            <i class="fa fa-cog" aria-hidden="true"></i> <span>Pengaturan</span>
          </a>
        </li>   
@endif

@if (auth()->user()->level == 2)
<li>
          <a href="{{route('barang.index')}}">
            <i class="fa fa-cubes"></i> <span>Barang</span>
          </a>
        </li>
<li>
  <a href="{{ route('penjualan.index') }}">
      <i class="fa fa-upload"></i> <span>Data Penjualan</span>
  </a>
</li>
<li>
  <a href="{{route('transaksi.baru')}}">
    <i class="fa fa-cart-arrow-down"></i> <span>Transaksi baru</span>
  </a>
</li>
<li>
  <a href="{{route('transaksi.index')}}">
    <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Lama</span>
  </a>
</li>
<li>
  <a href="{{route('pengeluaran.index')}}">
    <i class="fa fa-money" aria-hidden="true"></i> <span>Pengeluaran</span>
  </a>
</li>
<li class="header">REPORT</li> 
<li>
          <a href="{{route('laporanbarang.index')}}">
            <i class="fa fa-book" aria-hidden="true"></i> <span>Laporan Barang terjual</span>
          </a>
        </li>

<li>
  <a href="{{route('laporan.index')}}">
    <i class="fa fa-book" aria-hidden="true"></i> <span>Laporan pendapatan toko</span>
  </a>
</li>
@endif
@if (auth()->user()->level == 3)
<li>
  <a href="{{route('item.index')}}">
    <i class="fa fa-cubes"></i> <span>Item Penggilingan</span>
  </a>
</li>

<li>
  <a href="{{route('penggilingan.index')}}">
      <i class="fa fa-upload"></i> <span>Data Penggilingan</span>
  </a>
</li>
<li>
  <a href="{{route('order.baru')}}">
    <i class="fa fa-cart-arrow-down"></i> <span>Penggilingan baru</span>
  </a>
</li>
<li>
  <a href="{{route('order.index')}}">
    <i class="fa fa-cart-arrow-down"></i> <span>Penggilingan Aktif</span>
  </a>
</li>
 <li>
    <a href="{{route('pengeluaranbakso.index')}}">
      <i class="fa fa-money" aria-hidden="true"></i> <span>Pengeluaran Gilingan</span>
    </a>
  </li>
  <li>
    <a href="{{route('laporanbakso.index')}}">
      <i class="fa fa-book" aria-hidden="true"></i> <span>Laporan Pendapatan Gilingan</span>
    </a>
  </li>
@endif
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>