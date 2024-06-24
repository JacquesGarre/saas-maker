import { Component, Input } from '@angular/core';
import { FormConfig } from './form-config.interface';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { FieldComponent } from './field/field.component';
import { CommonModule } from '@angular/common';
import { FieldValidatorConfig } from './field/field-validator-config.interface';
import { FieldConfig } from './field/field-config.interface';

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

  constructor(private fb: FormBuilder) {}

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
      }
    }
    return validators;
  }

  onSubmit(): void {
    if (this.formGroup && this.formGroup.valid) {
      this.config.submitAction(this.formGroup.value);
    }
  }

}
