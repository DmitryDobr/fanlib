import React from 'react'

import { useAuth } from '../hoc/useAuth'
import { useState, useEffect } from "react"
import axios, { isAxiosError } from "axios";

import { WorkOverview } from '../components/WorkOverview';
import { Link } from 'react-router-dom';

import { useOutlet } from 'react-router-dom';

const UserCollectionsPage = () => {
    let {user} = useAuth();
    const outlet = useOutlet();
    const [collections, setCollections] = useState([]);

    useEffect(() => loadCollections,[user.user_id])

    async function loadCollections() {
        axios
        .post(`http://fanlib-api.ru/studio/collections`, null, {params: {
            'user_id': user.user_id,
        }})
        .then((response) => {
            setCollections(response.data);
            console.log(response.data)
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
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
            </div>
        )
    }
    else {
        return outlet;
    }
}

export {UserCollectionsPage}