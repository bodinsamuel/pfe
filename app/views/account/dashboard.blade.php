@extends('layouts.master')

@section('content')
    {{ HTML::linkRoute('account_edit', 'Edit' ) }}
    {{ HTML::linkRoute('account_address', 'Addresses' ) }}
    {{ HTML::linkRoute('account_alert', 'Alerts' ) }}
    {{ HTML::linkRoute('account_favorite', 'Favorites' ) }}


    {{ HTML::linkRoute('account_deactivate', 'Deactivate my account' ) }}
@stop
