import { Injectable } from '@angular/core';
import { jwtDecode } from 'jwt-decode';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  constructor() { }

  isAuthenticated(): boolean {
    const token = sessionStorage.getItem('jwt');
    if (!token) {
      return false;
    }
    try {
      const decodedToken: any = jwtDecode(token);
      const currentTime = Math.floor(new Date().getTime() / 1000);
      return decodedToken.exp > currentTime;
    } catch (error) {
      return false;
    }
  }
}