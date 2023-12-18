import React from 'react'

import axios, {isAxiosError} from 'axios'
import { useParams } from 'react-router-dom'

import { useState } from 'react'
import { useEffect } from 'react'

import { WorkOverview } from '../WorkOverview'

import { useAuth } from '../../hoc/useAuth'

import { Notfoundpage } from '../../pages/NotFoundPage'

const Collection = () => {

    const {idCollection} = useParams()
    let [works, setWorks] = useState([]);
    
    let {user} = useAuth();

    useEffect(() => {loadWorks()}, [idCollection]);

    async function loadWorks() {
        axios
        .post(`http://fanlib-api.ru/studio/collectionWorks`, null, {params: {
            'collection_id': idCollection,
            'user_id': user.user_id,
        }})
        .then((response) => {
            setWorks(response.data);
            // console.log(response.data)
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }

    async function DeleteWorkFromCollection (event) {
        let CWid = event.target.id;

        axios
        .post(`http://fanlib-api.ru/delete/workCollection`, null, {params: {
            'id': CWid,
            'user_id': user.user_id,
        }})
        .then((response) => {
            if (response.data.status === true)
            {
                loadWorks();
            }
            // console.log(response.data)
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }

    if (works.status !== false)
    {
        return (
            <div className='content'>
                <h1>Collection {idCollection} {user.user_id}</h1>
                <div className="CollectionPage-Works">
                    {
                        works.length > 0 ? (
                            works.map(el => (
                                <div key = {el.work_id} className='CollectionPage-Work'>
                                    <WorkOverview WorkId={el.work_id}/>
                                    <div className='CollectionPage-Del' id={el.COLL_WORK_id} onClick={DeleteWorkFromCollection}></div>
                                </div>
                            ))
                        ) : <p>Нет работ в коллекции</p>
                    }
                </div>
            </div>
        )
    }
    else
    {
        return(<Notfoundpage />)
    }
}

export {Collection}