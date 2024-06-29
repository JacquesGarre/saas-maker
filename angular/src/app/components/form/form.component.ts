import { Component, Input } from '@angular/core';
import { FormConfig } from './form-config.interface';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { FieldComponent } from './field/field.component';
import { CommonModule } from '@angular/common';
import { FieldValidatorConfig } from './field/field-validator-config.interface';
import { FieldConfig } from './field/field-config.interface';
import { matchValidator } from './custom-validators/match-validator';
import { ToasterComponent } from '../toaster/toaster.component';
import { Router } from '@angular/router';
import { ToasterConfig } from '../toaster/toaster-config.interface';


@Component({
  selector: 'app-form',
  standalone: true,
  imports: [
    FieldComponent,
    FormsModule, 
    ReactiveFormsModule,
    CommonModule,
    ToasterComponent
  ],
  templateUrl: './form.component.html',
  styleUrl: './form.component.scss'
})
export class FormComponent {

  @Input() config!: FormConfig;

  formGroup!: FormGroup;
  submitting: boolean = false;
  toasterConfig!: ToasterConfig;

  constructor(
    private fb: FormBuilder, 
    private router: Router
  ) {}

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
      this.config.submitAction(this.formGroup.value).subscribe({
        next: (response: any) => {
          this.toasterConfig = {
            message: response.message ?? 'Form submitted successfully',
            show: true,
            class: 'bottom-4 right-4 max-w-xs w-full bg-green-400'
          }   
          if (this.config.afterSubmitRedirection) {
            this.router.navigate(
              [this.config.afterSubmitRedirection.route], 
              this.config.afterSubmitRedirection.extras
            );
          }
        },
        error: (error: any) => {
          this.toasterConfig = {
            message: error.error.message ?? 'An error occured while submitting the form',
            show: true,
            class: 'bottom-4 right-4 max-w-xs w-full bg-red-400'
          }   
        }
      });

      setTimeout(() => {
        this.toasterConfig.show = false;
        this.submitting = false;
      }, 3000);
    } else {
      this.submitting = false;
    }
  }

}
