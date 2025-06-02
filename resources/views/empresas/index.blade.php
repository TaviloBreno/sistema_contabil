@extends('app')

@section('title', 'Empresas')

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Striped Full Width Table</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Task</th>
                    <th>Progress</th>
                    <th style="width: 40px">Label</th>
                </tr>
                </thead>
                <tbody>
                <tr class="align-middle">
                    <td>1.</td>
                    <td>Update software</td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                        </div>
                    </td>
                    <td><span class="badge text-bg-danger">55%</span></td>
                </tr>
                <!-- ... demais linhas ... -->
                </tbody>
            </table>
        </div>
    </div>
@endsection
