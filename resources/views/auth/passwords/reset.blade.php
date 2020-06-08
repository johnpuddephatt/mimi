@extends('layouts.app')

@section('content')

  <section class="section is-medium">
   <div class="container">
     <div class="columns is-centered">
       <div class="column is-6-tablet is-5-desktop is-4-widescreen is-paddingless">
         <form action="{{ route('password.request') }}" method="POST" class="box">
            @csrf
            <h3 class="title has-text-centered">Dimenticato? <span class="emoji">🤔</span></h3>
            <p class="subtitle has-text-centered">Change your password below</p>
            @if (session('status'))
              <div class="notification is-primary">
                {{ session('status') }}
              </div>
            @endif
           <div class="field">
             <label for="email" class="label">Email</label>
             <div class="control has-icons-left">
               <input name="email" type="email" placeholder="e.g. bobsmith@gmail.com" class="input" required>
               <span class="icon is-left"><i class="mdi mdi-email mdi-24px"></i></span>
             </div>
           </div>

           <div class="field">
             <label for="password" class="label">Password</label>
             <div class="control has-icons-left">
               <input name="password" type="password" placeholder="*******" class="input" required>
               <span class="icon is-left"><i class="mdi mdi-key mdi-24px"></i></span>
             </div>
           </div>

           <div class="field">
             <button type="submit" class="button is-primary is-fullwidth">
               Reset password
             </button>
           </div>
         </form>

       </div>
     </div>
   </div>
 </section>

@endsection
