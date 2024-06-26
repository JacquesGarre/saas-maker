import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { User } from '../models/user.interface';
import { environment } from '../../environments/environment';
import { Application } from '../models/application.interface';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  private apiUrl = environment.apiUrl;
  private apiKey = environment.apiKey; 

  constructor(private http: HttpClient) { }

  headers(): HttpHeaders {
    const jwt = sessionStorage.getItem('jwt');
    return new HttpHeaders({ 
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${jwt}`
    });
  }

  createUser(user: User): Observable<any> {
    const headers = new HttpHeaders({ 
      'Content-Type': 'application/json',
      'X-API-KEY': this.apiKey
    });
    let payload = {
      id: user.id,
      email: user.email,
      first_name: user.firstName,
      last_name: user.lastName,
      password: user.password
    }
    return this.http.post<any>(`${this.apiUrl}/users`, payload, { headers });
  }

  verifyUser(token?: string): Observable<any> {
    const headers = new HttpHeaders({ 
      'Content-Type': 'application/json',
      'X-API-KEY': this.apiKey
    });
    let payload = {
      token: token
    }
    return this.http.post<any>(`${this.apiUrl}/users/verify`, payload, { headers });
  }

  loginUser(user: User): Observable<any> {
    const headers = new HttpHeaders({ 
      'Content-Type': 'application/json',
      'X-API-KEY': this.apiKey
    });
    let payload = {
      email: user.email,
      password: user.password
    }
    return this.http.post<any>(`${this.apiUrl}/login`, payload, { headers });
  }

  createApplication(application: Application): Observable<any> {
    const headers = this.headers();
    let payload = {
      id: application.id,
      name: application.name,
      subdomain: application.subdomain
    }
    return this.http.post<any>(`${this.apiUrl}/applications`, payload, { headers });
  }

}
