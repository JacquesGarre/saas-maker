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
import { ToasterConfig } from '../toaster/toaster-config.interface'; // TODO: Toaster service to display error-->


@Component({
  selector: 'app-form',
  standalone: true,
  imports: [
    FieldComponent,
    FormsModule, 
    ReactiveFormsModule,
    CommonModule,
    ToasterComponent // TODO: Toaster service to display error-->
  ],
  templateUrl: './form.component.html',
  styleUrl: './form.component.scss'
})
export class FormComponent {

  @Input() config!: FormConfig;

  formGroup!: FormGroup;
  submitting: boolean = false;
  toasterConfig!: ToasterConfig; // TODO: Toaster service to display error-->

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
        field.value ?? '',
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
    if (!this.formGroup || !this.formGroup.valid) {
      this.submitting = false;
      return;
    }
    this.handleSubmit();
  }

  handleSubmit(): void {
    this.config.submitAction(this.formGroup.value).subscribe({
      next: (response: any) => {
        this.handleSuccessToaster(response);
        this.handleAfterSubmitAction(response);
        this.handleAfterSubmitRedirection()
      },
      error: (error: any) => {
        this.handleErrorToaster(error)
      }
    });
    this.hideToaster();
  }

  handleSuccessToaster(response: any): void {
    this.toasterConfig = { // TODO: Toaster service to display success-->
      message: response.message ?? 'Form submitted successfully',
      show: true,
      class: 'bottom-4 right-4 max-w-xs w-full bg-green-400'
    }   
  }

  handleErrorToaster(error: any): void {
    this.toasterConfig = { // TODO: Toaster service to display error-->
      message: error.error.message ?? 'An error occured while submitting the form',
      show: true,
      class: 'bottom-4 right-4 max-w-xs w-full bg-red-400'
    }   
  }

  handleAfterSubmitAction(response: any): void {
    if (this.config.afterSubmitAction) {
      this.config.afterSubmitAction(response)
    }
  }

  handleAfterSubmitRedirection(): void {
    if (this.config.afterSubmitRedirection) {
      this.router.navigate(
        [this.config.afterSubmitRedirection.route], 
        this.config.afterSubmitRedirection.extras
      );
    }
  }


  hideToaster(): void
  {
    setTimeout(() => {
      this.toasterConfig.show = false; // TODO: Should be in toaster service to hide
      this.submitting = false;
    }, 3000);
  }

}
