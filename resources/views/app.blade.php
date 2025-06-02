 @include('layouts.header')
@include('layouts.sidebar')
    <!--begin::App Main-->
 <main class="app-main content-wrapper">
     <section class="content pt-3">
         <div class="container-fluid">
             @yield('content')
         </div>
     </section>
 </main>
 <!-- end::App Main -->
    <!--end::App Main-->
 @include('layouts.footer')

