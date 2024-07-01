import { Component } from '@angular/core';
import { ApiService } from '../../services/api.service';
import { FormConfig } from '../form/form-config.interface';
import { Observable } from 'rxjs';
import { FormComponent } from '../form/form.component';
import { CommonModule } from '@angular/common';
import { Application } from '../../models/application.interface';
import { v4 as uuidv4 } from 'uuid';
import { ModalService } from '../../services/modal.service';

@Component({
  selector: 'app-create-application-form',
  standalone: true,
  imports: [
    FormComponent,
    CommonModule
  ],
  templateUrl: './create-application-form.component.html',
  styleUrl: './create-application-form.component.scss'
})
export class CreateApplicationFormComponent {

  constructor(
    private apiService: ApiService,
    private modalService: ModalService  
  ) {}

  createApplicationFormConfig: FormConfig = {
    submitBtnLabel: 'Create',
    submitAction: (application: Application): Observable<any> => {
      application.id = application.id ?? uuidv4();
      return this.apiService.createApplication(application);
    },
    afterSubmitAction: (response: any): Observable<any> => {
      this.modalService.close();
      return new Observable();
    },
    fields: [
      {
        formControlName: 'name',
        type: 'text',
        label: 'Name',
        required: true,
        validators: [
          {
            type: 'required',
            errorMessage: 'Name is required'
          }
        ]
      },
      {
        formControlName: 'subdomain',
        type: 'text',
        label: 'Subdomain',
        required: true,
        validators: [
          {
            type: 'required',
            errorMessage: 'Subdomain is required'
          },
          {
            type: 'pattern',
            errorMessage: 'Invalid subdomain',
            value: '^[a-z0-9]+(?:-[a-z0-9]+)*$'
          }
        ]
      },
    ]
  }

}
