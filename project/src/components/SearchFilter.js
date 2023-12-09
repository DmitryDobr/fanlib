import React, { useState } from 'react'

const Filter = ({fandomQuerry, setSearchParams}) => {
    // получили аргументами запрос и функию

    const [search, setSearch] = useState(fandomQuerry)
    // const [search, setSearch] = useState(postQuerry)

    // const[searchParams, setSearchParams] = useSearchParams();

    const handleSubmit = (event) => {
        event.preventDefault();
        const form = event.target;

        const query = form.search.value;

        
        setSearchParams({name: query});
        // обновление строки с get параметрами вызовом переданной функции из BlogPage, 
        // которая изменяет поисковые параметры
    }


  return (
    <div>
        {/* При отправке формы вызывается handleSubmit */}
        <form className='Input-form' autoComplete="off" onSubmit={handleSubmit}>
            {/* При изменении текста будет изменяться search */}
            <input className='Input-text' placeholder='Название фандома' type='text' name="search" value={search} onChange={event => setSearch(event.target.value)}></input>
            <input className='Input-button' type='submit' value="Поиск"></input>
        </form>
    </div>
  )
}

export default Filter