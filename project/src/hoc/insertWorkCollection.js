import { useEffect } from 'react';
import axios, {isAxiosError} from 'axios';
import { useAuth } from './useAuth'

import { useState } from 'react';
import { Menu, MenuItem, FocusableItem, MenuButton } from '@szhsin/react-menu';
import '@szhsin/react-menu/dist/index.css';

const InsertWorkCollection = (props) => {
    let {user} = useAuth();
    var WorkId = props.WorkId

    const [collections, setCollections] = useState([]);
    useEffect(() => loadCollections,[user.user_id])

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

    async function addWorkCollection(collection_id) {
        axios
        .post(`http://fanlib-api.ru/insert/workcollection`, null, {params: {
            'user_id': user.user_id,
            'collection_id': collection_id,
            'work_id': WorkId,
        }})
        .then((response) => {
            if (response.data.status === true)
            {
                alert(response.data.message)
            }
        })
        .catch((error) => {
            if (isAxiosError(error))
            {
                console.log(error.response.data.message);
            }
        });
    }

    if (user)
    {
        return (
            <>
                <Menu menuButton={<MenuButton>+</MenuButton>} className='AddCollection-Button'>
                    {
                        collections.map(el => (
                            <MenuItem key={el.id} onClick={() => addWorkCollection(el.id)}>{el.name}</MenuItem>
                        ))
                    }
                </Menu>
            </>
        )
    }
    
}

export {InsertWorkCollection}