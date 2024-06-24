import { AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';

export function matchValidator(formControlName: string): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    if (!control.parent) {
      return null;
    }
    const formControl = control.parent.get(formControlName);
    if (!formControl) {
      return null;
    }
    const mismatch = formControl.value !== control.value;
    return mismatch ? { match: true } : null;
  };
}