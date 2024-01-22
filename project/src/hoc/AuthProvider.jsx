// тут находится провайдер для авторизации

import { createContext , useState } from "react";
import axios, {isAxiosError} from "axios";



export const AuthContext = createContext(null);


export const AuthProvider = ({children}) => {

    const [user, setUser] = useState(null);

    // передаем информацию о пользователе и функцию, которая должна переносить на др.страницу с заменой истории
    const signin = (newUser, newPass, callback) => {
        var bodyFormData = new FormData();
        bodyFormData.append('email', newUser);
        bodyFormData.append('password', newPass);
        
        axios
        .post(`http://fanlib-api.ru/user/login`, bodyFormData, { params: {}})
        .then((response) => {
            
            console.log(response.data);

            if (response.data.status) {
                setUser(response.data);
                callback();
                // alert("Залогинено");
            }
            else {
                setUser(null);
                // callback();
                alert("Ошибка авторизации");
            }
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }
    // функция разлогинивания пользователя
    const signout = (callback) => {
        setUser(null);
        callback();
        // alert("Разлогинено");
    }

    // функция для регистрации пользователя
    const registrate = (email,password,username,callback) => {
        // console.log(email,password,username);
        var bodyFormData = new FormData();
        bodyFormData.append('email', email);
        bodyFormData.append('password', password);
        bodyFormData.append('nickname', username);

        axios
        .post(`http://fanlib-api.ru/user/register`, bodyFormData, { params: {}})
        .then((response) => {
            
            console.log(response.data);

            if (response.data.status)
            {
                // setUser(response.data);
                signin(email, password, callback);
                // callback();
                // alert("Залогинено");
            }
            else
            {
                setUser(null);
                // callback();
                alert("Не удалось зарегистрировать пользователя");
            }
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }

    const value = {user, signin, signout, registrate}

    return <AuthContext.Provider value={value}>
        {children}
    </AuthContext.Provider>
}

/*

    Есть некий провайдер. В нем есть функции, эмулирующие авторизацию и разлогинивание

    в данном случае просто все приложение обеспечивается информацией о текущем пользователе (строка)
    и методами логина и разлогина

    RequireAuth - обертка, которая позволяет узнать откуда мы пришли при помощи state{{from: location}}
    
    
    Если есть пользователь, то при попытке попасть на приватный роут, то приватный роут открывается
    иначе, переход на логин


*/