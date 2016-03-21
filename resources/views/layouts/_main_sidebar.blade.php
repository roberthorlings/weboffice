  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset("/assets/img/robert.jpg") }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Robert Horlings</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        
        <li>
          <a href="{{ url( 'workinghours' )}}">
            <i class="fa fa-clock-o"></i> <span>Working hours</span>
          </a>
        </li>        

        <li class="active treeview">
          <a href="#">
            <i class="fa fa-eur"></i> <span>Finances</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url( 'transaction' )}}"><i class="fa fa-circle-o"></i> Transactions</a></li>
            <li><a href="{{ url( 'statement' )}}"><i class="fa fa-circle-o"></i> Statements</a></li>
            <li><a href="{{ url( 'results' )}}"><i class="fa fa-circle-o"></i> Results</a></li>
            <li><a href="{{ url( 'balance' )}}"><i class="fa fa-circle-o"></i> Balance</a></li>
            <li><a href="{{ url( 'saldo' )}}"><i class="fa fa-circle-o"></i> Amounts due</a></li>
            
           	<li> 
                  <a href="#"><i class="fa fa-circle-o"></i> More <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
		            <li><a href="{{ url( 'asset' )}}"><i class="fa fa-circle-o"></i> Assets</a></li>
		            <li><a href="{{ url( 'ledger' )}}"><i class="fa fa-circle-o"></i> Ledgers</a></li>
		            <li><a href="{{ url( 'travel' )}}"><i class="fa fa-circle-o"></i> Travel registration</a></li>
		            <li><a href="{{ url( 'transaction/import' )}}"><i class="fa fa-circle-o"></i> Import</a></li>
                  </ul>
            </li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-file-text-o"></i> <span>Documents</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url( 'invoice' )}}"><i class="fa fa-circle-o"></i> Invoices</a></li>
            <li><a href="{{ url( 'quote' )}}"><i class="fa fa-circle-o"></i> Quotes</a></li>
            <li><a href="{{ url( 'invoice/project' )}}"><i class="fa fa-circle-o"></i> Add project invoice</a></li>
            <li><a href="{{ url( 'invoice/add' )}}"><i class="fa fa-circle-o"></i> Add normal invoice</a></li>
            
           	<li> 
                  <a href="#"><i class="fa fa-circle-o"></i> More <i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
		            <li><a href="{{ url( 'quote/add' )}}"><i class="fa fa-circle-o"></i> Add quote</a></li>
		            <li><a href="{{ url( 'invoice/credit' )}}"><i class="fa fa-circle-o"></i> Add credit note</a></li>
		            <li><a href="{{ url( 'invoice/receive' )}}"><i class="fa fa-circle-o"></i> Receive invoice</a></li>
		            <li><a href="{{ url( 'entries/costs' )}}"><i class="fa fa-circle-o"></i> Report costs</a></li>
                  </ul>
            </li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-file-text-o"></i> <span>CRM</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url( 'relation' )}}"><i class="fa fa-circle-o"></i> Relations</a></li>
            <li><a href="{{ url( 'project' )}}"><i class="fa fa-circle-o"></i> Projects</a></li>
          </ul>
        </li>
                
        <li class="treeview">
          <a href="#">
            <i class="fa fa-ellipsis-v"></i> <span>Other</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url( 'export' )}}"><i class="fa fa-circle-o"></i> Export</a></li>
            <li><a href="{{ url( 'export/year/2015' )}}"><i class="fa fa-circle-o"></i> Overview 2015</a></li>
          </ul>
        </li>        
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
