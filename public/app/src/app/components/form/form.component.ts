import { Component, Input } from '@angular/core';
import { FormConfig } from './form-config.interface';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { FieldComponent } from './field/field.component';
import { CommonModule } from '@angular/common';
import { FieldValidatorConfig } from './field/field-validator-config.interface';
import { FieldConfig } from './field/field-config.interface';
import { matchValidator } from './custom-validators/match-validator';
import { ApiService } from '../../services/api.service';


@Component({
  selector: 'app-form',
  standalone: true,
  imports: [
    FieldComponent,
    FormsModule, 
    ReactiveFormsModule,
    CommonModule
  ],
  templateUrl: './form.component.html',
  styleUrl: './form.component.scss'
})
export class FormComponent {

  @Input() config!: FormConfig;

  formGroup!: FormGroup;
  submitting: boolean = false;

  constructor(private fb: FormBuilder, private apiService: ApiService) {}

  ngOnInit(): void
  {
    let formGroupConfig: any = this.getFormGroupConfig(this.config.fields);
    this.formGroup = this.fb.group(formGroupConfig);
  }

  getFormGroupConfig(fieldsConfig: FieldConfig[]): any {
    let formGroupConfig: any = {};
    for (const field of fieldsConfig) {
      formGroupConfig[field.formControlName] = [
        field.value,
        this.getFieldValidators(field.validators)
      ];
    }
    return formGroupConfig;
  }

  getFieldValidators(fieldValidatorsConfig: FieldValidatorConfig[]): any {
    let validators: any = [];
    for (const validator of fieldValidatorsConfig) {
      switch (validator.type) {
        case 'required':
          validators.push(Validators.required)
        break;
        case 'email':
          validators.push(Validators.email)
        break;
        case 'pattern':
          validators.push(Validators.pattern(validator.value ? validator.value.toString() : ''))
        break;
        case 'minlength':
          validators.push(Validators.minLength(validator.value ? parseInt(validator.value.toString()) : 0))
        break;
        case 'maxlength':
          validators.push(Validators.maxLength(validator.value ? parseInt(validator.value.toString()) : 0))
        break;
        case 'match':
          validators.push(matchValidator(validator.value ? validator.value.toString() : ''))
        break;
      }
    }
    return validators;
  }

  onSubmit(): void {
    this.submitting = true;
    if (this.formGroup && this.formGroup.valid) {
      this.config.submitAction(this.apiService, this.formGroup.value);
    }
    //this.submitting = false;
  }

}
