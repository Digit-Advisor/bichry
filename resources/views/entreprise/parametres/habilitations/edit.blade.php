@extends('layouts.admin.admin')

@section('styles')
@include('layouts.admin.required.extensions.styles.datatable')
@endsection

@section('content')
@if (Auth::user()->role == "entreprise" OR Auth::user()->role == "admin" OR Auth::user()->role == "cabinet")
@php
$PackageEntreprise = $Abonnement->where('entreprise_id', Auth::user()->entreprise_id)->first()->package_id;
// $PackageEntreprise = $Packages::where('package_id', $AbonnementEntreprise->package_id)->first();
$ModuleEntreprise = $ModulePack->where('package_id', $PackageEntreprise);
@endphp
@endif
@include('layouts.admin.required.includes.pageName')

  <div class="row g-0">
    <div class="col-lg-4 pe-lg-2">
      <div class="card mb-3">
        <div class="card-header bg-light">
          <h5 class="mb-0">Liste des habilitations</h5>
        </div>

        <div class="card-body">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="heading1">
                    <button class="accordion-button btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">Ajouter une habilitation</button>
                  </h2>
                  <div class="accordion-collapse collapse show " id="collapse1" aria-labelledby="heading1" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="{{route('entreprise.habilitation.updateName', $habilitation->id)}}" class="row g-3 needs-validation" novalidate="" method="POST"> @csrf
                            <div class="col-md-4-12">
                                <label class="form-label" for="habilitation">Nom de l'habilitation exemple: <span class="text-warning">Comptable</span></label>
                                <input class="form-control" name="habilitation" id="habilitation" value="{{$habilitation->habilitation}}" type="text"  required="" />
                            </div>

                            <div class="col-md-4-12" hidden>
                                <input class="form-control" name="entreprise_id" value="{{Auth::user()->entreprise_id}}" id="entreprise_id" type="text"  required="" />
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Mettre à jour</button>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>

              </div>


            <table class="table fs--1 mb-0 overflow-hidden">
                <thead class="bg-100 text-800">
                  <tr>
                    <th class="w-100 text-start ">Nom de l'habilitation</th>
                    <th class="text-nowrap text-end ">Action</th>

                  </tr>
                </thead>
                <tbody>
                    @foreach ($Habilitations->where('entreprise_id', 0) as $habil)
                    <tr>
                        <td class="text-truncate">{{$habil->habilitation}}</td>
                        <td class="text-truncate d-flex justify-content-between">
                            <a class=" m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Vous ne pouvez pas modifier une habilitation système par défaut">
                                <i class="fas fa-pen-square mr-2" style="font-size: 1.4em; "></i>
                            </a>

                            <a class=" m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Vous ne pouvez pas supprimer une habilitation système par défaut">
                                <i class="far fa-trash-alt text-danger" style="font-size: 1.4em"></i>
                            </a>

                        </td>
                      </tr>

                    @endforeach

                    @foreach ($Habilitations->where('entreprise_id', Auth::user()->entreprise_id) as $habil)
                    <tr>
                        <td class="text-truncate">{{$habil->habilitation}}</td>
                        <td class="text-truncate text-start">
                            <a href="{{route('entreprise.habilitation.details',$habil->id )}}"> <i class="fas fa-pen-square mr-2" style="font-size: 1.4em; margin-right:5px"></i></a>
                            <a href="{{route('entreprise.habilitation.delete',$habil->id )}}"> <i class="far fa-trash-alt text-danger" style="font-size: 1.4em"></i></a>
                        </td>
                      </tr>

                    @endforeach

                </tbody>
            </table>
        </div>
      </div>


    </div>
    <div class="col-lg-8 ps-lg-2">
      <div class="sticky-sidebar">
        <div class="card mb-3">
            <form style="padding-left: 1em !important" action="{{route('entreprise.habilitation.update', $habilitation->id)}}" method="post">@csrf

                <div class="card-header bg-light d-flex justify-content-between">
                    <h5 class="mb-0">Liste des habilitations du role {{$habilitation->habilitation}}
                    </h5> <button type="submit" class="btn btn-success"> enregistrer</button>
                </div>
                <div class="card-body bg-light">

                        @foreach ($AllModules as $AllModule)
                            @foreach ($ModuleEntreprise as $module)
                                @if ($module->module_id == $AllModule->id)
                                <div class=" col-md-12 ">
                                    <h5>Module: {{$AllModules->find($module->module_id)->nom}}</h5>
                                </div>
                                <div class="col-md-12 row" style="padding-left: 1em !important">

                                    <div class="ml-5 col-md-3 form-check form-switch">
                                        <label class="form-check-label " for="{{"voir".$module->module_id}}">Peut afficher/voir</label>

                                        <input class="form-check-input  @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()) @if ( $habilitation->fonctionnalites->where('module_id',$module->module_id )->first()->voir == "yes") bg-success @endif @endif" value="1" name="{{"voir".$module->module_id}}" id="{{"voir".$module->module_id}}" type="checkbox"  @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()) @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()->voir == "yes") checked @endif @endif />
                                    </div>
                                    <div class="ml-5 col-md-3 form-check form-switch">
                                        <label class="form-check-label" for="{{"ajouter".$module->module_id}}">Peut Ajouter</label>

                                        <input class="form-check-input @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()) @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()->ajouter == "yes") bg-success @endif @endif"  value="1" id="aa" name="{{"ajouter".$module->module_id}}" id="{{"ajouter".$module->module_id}}"  @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()) @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()->ajouter == "yes") checked @endif @endif type="checkbox"  />
                                    </div>
                                    <div class="ml-5 col-md-3 form-check form-switch">
                                        <label class="form-check-label" for="{{"modifier".$module->module_id}}">Peut modifier</label>

                                        <input class="form-check-input @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()) @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()->modifier == "yes") bg-success @endif @endif" value="1" name="{{"modifier".$module->module_id}}" id="{{"modifier".$module->module_id}}"  @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()) @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()->modifier == "yes") checked @endif @endif type="checkbox"  />
                                    </div>
                                    <div class="ml-5 col-md-3 form-check form-switch">
                                        <label class="form-check-label" for="{{"supprimer".$module->module_id}}">Peut Supprimer</label>

                                        <input class="form-check-input @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()) @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()->supprimer == "yes") bg-success @endif @endif" value="1" name="{{"supprimer".$module->module_id}}" id="{{"supprimer".$module->module_id}}"   @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()) @if ($habilitation->fonctionnalites->where('module_id',$module->module_id )->first()->supprimer == "yes") checked @endif @endif type="checkbox"   />
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endforeach
                        <button type="submit" class="btn mt-3 btn-success w-100"> enregistrer</button>


                </div>
            </form>

        </div>
      </div>
    </div>
  </div>

@endsection



