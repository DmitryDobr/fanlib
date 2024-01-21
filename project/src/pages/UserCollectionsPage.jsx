import React from 'react'

import { useAuth } from '../hoc/useAuth'
import { useState, useEffect } from "react"
import axios, { isAxiosError } from "axios";


import { Link } from 'react-router-dom';

import { useOutlet } from 'react-router-dom';

const UserCollectionsPage = () => {
    let {user} = useAuth();
    const outlet = useOutlet();
    const [collections, setCollections] = useState([]);

    useEffect(() => loadCollections,[user.user_id, outlet])

    // прогрузка коллекций работ
    async function loadCollections() {
        axios
        .post(`http://fanlib-api.ru/studio/collections`, null, {params: {
            'user_id': user.user_id,
        }})
        .then((response) => {
            setCollections(response.data);
            // console.log(response.data)
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }

    // добавление коллекции
    async function addCollection(event) {
        event.preventDefault()
        const form = event.target

        const CollName = form.CollectionName.value // название новой коллекции
        const userId = user.user_id // id пользователя

        axios
        .post(`http://fanlib-api.ru/insert/collection`, null, {params: {
            user_id: userId ,
            name: CollName,
        }
        })
        .then((response) => {
            // console.log(response.data);
            alert(response.data.message)

            if (response.data.status === true)
            {
                loadCollections();
            }
        })
        .catch((error) => {
            console.log(error)
            if (isAxiosError)
            {
                // console.log(error.response.data);
                alert(error.response.data.message)
            }
        });
    }

    if (!outlet)
    {
        return (
            <div className='content'>
                <h1>Мои коллекции работ</h1>
                <div className='collections'>
                    {
                        collections.map(el => (
                            <div className='collection' key={el.id}>
                                <span>
                                    <Link className='collection-link' to={`./${el.id}`}>{el.name}</Link>
                                    <span className='collection-text'> ({el.count})</span>
                                </span>
                                <span className='collection-text delete-collection'> Удалить</span>
                            </div>
                        ))
                    }
                </div>
                <form className='EditForm' onSubmit={addCollection}>
                    <input className='EditForm-text' name="CollectionName" placeholder='Название новой коллекции'></input>
                    <input className='LoginForm-button' type='submit' value={'Создать'}></input>
                </form>
            </div>
        )
    }
    else {
        return outlet;
    }
}

export {UserCollectionsPage}