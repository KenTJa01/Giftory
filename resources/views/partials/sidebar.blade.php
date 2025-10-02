<nav id="sidebar" style="position: sticky">
    <div class="p-4 mt-2">
        {{-- <a href="#" class="img logo mb-5" style="background-image: url(images/logo-giftory.png);"></a> --}}
        <img src="{{ asset('images/logo-giftory.png') }}" alt="" style="width: 110px; margin-left: 30px">

        <ul class="list-unstyled components mt-4 mb-5">

            {{-- MENU HOME --}}
            <li class="{{ Request::routeIs('home') ? 'active' : '' }}">
                <a href="/home">Home</a>
            </li>

            {{-- MENU DATA MASTER --}}
            <li id="master" class="d-none">
                <a href="#masterSubmenu" id="masterLabel" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle collapsed">Data Master</a>
                <ul class="collapse list-unstyled" id="masterSubmenu">

                    {{-- USERS --}}
                    <li class="{{ Request::routeIs('master-user') ? 'active' : '' }} d-none" id="master-user">
                        <a href="/master-user">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                            Users
                        </a>
                    </li>

                    {{-- PROFILES --}}
                    <li class="{{ Request::routeIs('master-profile') ? 'active' : '' }} d-none" id="master-profile">
                        <a href="/master-profile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5ZM9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8Zm1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5Zm-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96c.026-.163.04-.33.04-.5ZM7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0Z"/>
                            </svg>
                            Profiles
                        </a>
                    </li>

                    {{-- SITES --}}
                    <li class="{{ Request::routeIs('master-site') ? 'active' : '' }} d-none" id="master-site">
                        <a href="/master-site">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe2" viewBox="0 0 16 16">
                                <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855-.143.268-.276.56-.395.872.705.157 1.472.257 2.282.287V1.077zM4.249 3.539c.142-.384.304-.744.481-1.078a6.7 6.7 0 0 1 .597-.933A7.01 7.01 0 0 0 3.051 3.05c.362.184.763.349 1.198.49zM3.509 7.5c.036-1.07.188-2.087.436-3.008a9.124 9.124 0 0 1-1.565-.667A6.964 6.964 0 0 0 1.018 7.5h2.49zm1.4-2.741a12.344 12.344 0 0 0-.4 2.741H7.5V5.091c-.91-.03-1.783-.145-2.591-.332zM8.5 5.09V7.5h2.99a12.342 12.342 0 0 0-.399-2.741c-.808.187-1.681.301-2.591.332zM4.51 8.5c.035.987.176 1.914.399 2.741A13.612 13.612 0 0 1 7.5 10.91V8.5H4.51zm3.99 0v2.409c.91.03 1.783.145 2.591.332.223-.827.364-1.754.4-2.741H8.5zm-3.282 3.696c.12.312.252.604.395.872.552 1.035 1.218 1.65 1.887 1.855V11.91c-.81.03-1.577.13-2.282.287zm.11 2.276a6.696 6.696 0 0 1-.598-.933 8.853 8.853 0 0 1-.481-1.079 8.38 8.38 0 0 0-1.198.49 7.01 7.01 0 0 0 2.276 1.522zm-1.383-2.964A13.36 13.36 0 0 1 3.508 8.5h-2.49a6.963 6.963 0 0 0 1.362 3.675c.47-.258.995-.482 1.565-.667zm6.728 2.964a7.009 7.009 0 0 0 2.275-1.521 8.376 8.376 0 0 0-1.197-.49 8.853 8.853 0 0 1-.481 1.078 6.688 6.688 0 0 1-.597.933zM8.5 11.909v3.014c.67-.204 1.335-.82 1.887-1.855.143-.268.276-.56.395-.872A12.63 12.63 0 0 0 8.5 11.91zm3.555-.401c.57.185 1.095.409 1.565.667A6.963 6.963 0 0 0 14.982 8.5h-2.49a13.36 13.36 0 0 1-.437 3.008zM14.982 7.5a6.963 6.963 0 0 0-1.362-3.675c-.47.258-.995.482-1.565.667.248.92.4 1.938.437 3.008h2.49zM11.27 2.461c.177.334.339.694.482 1.078a8.368 8.368 0 0 0 1.196-.49 7.01 7.01 0 0 0-2.275-1.52c.218.283.418.597.597.932zm-.488 1.343a7.765 7.765 0 0 0-.395-.872C9.835 1.897 9.17 1.282 8.5 1.077V4.09c.81-.03 1.577-.13 2.282-.287z"/>
                            </svg>
                            Sites
                        </a>
                    </li>

                    {{-- LOCATION --}}
                    <li class="{{ Request::routeIs('master-location') ? 'active' : '' }} d-none" id="master-location">
                        <a href="/master-location">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg>
                            Locations
                        </a>
                    </li>

                    {{-- PRODUCT CATEGORIES --}}
                    <li class="{{ Request::routeIs('master-product-category') ? 'active' : '' }} d-none" id="master-product-category">
                        <a href="/master-product-category">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tags-fill" viewBox="0 0 16 16">
                                <path d="M2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586V2zm3.5 4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                <path d="M1.293 7.793A1 1 0 0 1 1 7.086V2a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l.043-.043-7.457-7.457z"/>
                            </svg>
                            Product Categories
                        </a>
                    </li>

                    {{-- UNITS --}}
                    {{-- <li class="{{ Request::routeIs('master-unit') ? 'active' : '' }} d-none" id="master-unit">
                        <a href="/master-unit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.01-.003.268-.108a.75.75 0 0 1 .558 0l.269.108.01.003 6.97 2.789ZM10.404 2 4.25 4.461 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339L8 5.961 5.596 5l6.154-2.461L10.404 2Z"/>
                            </svg>
                            Units
                        </a>
                    </li> --}}

                    {{-- SUPPLIERS --}}
                    <li class="{{ Request::routeIs('master-supplier') ? 'active' : '' }} d-none" id="master-supplier">
                        <a href="/master-supplier">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                                <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                            </svg>
                            Suppliers
                        </a>
                    </li>

                </ul>
            </li>

            {{-- MENU RECEIVING --}}
            <li id="receiving" class="d-none">
                <a href="#receivingSubmenu" id="receivingLabel" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">Receiving</a>
                <ul class="collapse list-unstyled" id="receivingSubmenu">

                    {{-- LIST RECEIVING --}}
                    <li class="{{ Request::routeIs('list-receiving') ? 'active' : '' }} d-none" id="list-receiving">
                        <a href="/list-receiving">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                            </svg>
                            List
                        </a>
                    </li>

                    {{-- FORM RECEIVING --}}
                    <li class="{{ Request::routeIs('form-receiving') ? 'active' : '' }} d-none" id="form-receiving">
                        <a href="/form-receiving">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks" viewBox="0 0 16 16">
                                <path d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zM2 1a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm0 8a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2H2zm.854-3.646a.5.5 0 0 1-.708 0l-1-1a.5.5 0 1 1 .708-.708l.646.647 1.646-1.647a.5.5 0 1 1 .708.708l-2 2zm0 8a.5.5 0 0 1-.708 0l-1-1a.5.5 0 0 1 .708-.708l.646.647 1.646-1.647a.5.5 0 0 1 .708.708l-2 2zM7 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zm0-5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            Form
                        </a>
                    </li>

                </ul>
            </li>

            {{-- MENU EXPENDING --}}
            <li id="expending" class="d-none">
                <a href="#expendingSubmenu" id="expendingLabel" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle collapsed">Expending</a>
                <ul class="collapse list-unstyled" id="expendingSubmenu">

                    {{-- LIST EXPENDING --}}
                    <li class="{{ Request::routeIs('list-expending') ? 'active' : '' }} d-none" id="list-expending">
                        <a href="/list-expending">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                            </svg>
                            List
                        </a>
                    </li>

                    {{-- FORM EXPENDING --}}
                    <li class="{{ Request::routeIs('form-expending') ? 'active' : '' }} d-none" id="form-expending">
                        <a href="/form-expending">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks" viewBox="0 0 16 16">
                                <path d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zM2 1a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm0 8a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2H2zm.854-3.646a.5.5 0 0 1-.708 0l-1-1a.5.5 0 1 1 .708-.708l.646.647 1.646-1.647a.5.5 0 1 1 .708.708l-2 2zm0 8a.5.5 0 0 1-.708 0l-1-1a.5.5 0 0 1 .708-.708l.646.647 1.646-1.647a.5.5 0 0 1 .708.708l-2 2zM7 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zm0-5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            Form
                        </a>
                    </li>

                </ul>
            </li>

            {{-- MENU TRANSFER --}}
            <li id="transfer" class="d-none">
                <a href="#transferSubmenu" id="transferLabel" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle collapsed">Transfer</a>
                <ul class="collapse list-unstyled" id="transferSubmenu">

                    {{-- LIST TRANSFER --}}
                    <li class="{{ Request::routeIs('list-transfer') ? 'active' : '' }} d-none" id="list-transfer">
                        <a href="/list-transfer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                            </svg>
                            List
                        </a>
                    </li>

                    {{-- FORM TRANSFER --}}
                    <li class="{{ Request::routeIs('form-transfer') ? 'active' : '' }} d-none" id="form-transfer">
                        <a href="/form-transfer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks" viewBox="0 0 16 16">
                                <path d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zM2 1a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm0 8a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2H2zm.854-3.646a.5.5 0 0 1-.708 0l-1-1a.5.5 0 1 1 .708-.708l.646.647 1.646-1.647a.5.5 0 1 1 .708.708l-2 2zm0 8a.5.5 0 0 1-.708 0l-1-1a.5.5 0 0 1 .708-.708l.646.647 1.646-1.647a.5.5 0 0 1 .708.708l-2 2zM7 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zm0-5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            Form
                        </a>
                    </li>

                </ul>
            </li>

            {{-- MENU STOCK --}}
            <li id="stock" class="d-none">
                <a href="#stockSubmenu" id="stockLabel" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle collapsed">Stock</a>
                <ul class="collapse list-unstyled" id="stockSubmenu">

                    {{-- LIST STOCK --}}
                    <li class="{{ Request::routeIs('list-stock') ? 'active' : '' }} d-none" id="list-stock">
                        <a href="/list-stock">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                            </svg>
                            List
                        </a>
                    </li>

                    {{-- MOVEMENT STOCK --}}
                    <li class="{{ Request::routeIs('movement-stock') ? 'active' : '' }} d-none" id="movement-stock">
                        <a href="/movement-stock">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z"/>
                            </svg>
                            Movement
                        </a>
                    </li>

                </ul>
            </li>

            {{-- MENU STOCK OPNAME --}}
            <li id="stockOpname" class="d-none">
                <a href="#stockOpnameSubmenu" id="stockOpnameLabel" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle collapsed">Stock Opname</a>
                <ul class="collapse list-unstyled" id="stockOpnameSubmenu">

                    {{-- LIST STOCK OPNAME --}}
                    <li class="{{ Request::routeIs('list-stock-opname') ? 'active' : '' }} d-none" id="list-stock-opname">
                        <a href="/list-stock-opname">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                            </svg>
                            List
                        </a>
                    </li>

                    {{-- FORM STOCK OPNAME --}}
                    <li class="{{ Request::routeIs('form-stock-opname') ? 'active' : '' }} d-none" id="form-stock-opname">
                        <a href="/form-stock-opname">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks" viewBox="0 0 16 16">
                                <path d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zM2 1a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm0 8a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2H2zm.854-3.646a.5.5 0 0 1-.708 0l-1-1a.5.5 0 1 1 .708-.708l.646.647 1.646-1.647a.5.5 0 1 1 .708.708l-2 2zm0 8a.5.5 0 0 1-.708 0l-1-1a.5.5 0 0 1 .708-.708l.646.647 1.646-1.647a.5.5 0 0 1 .708.708l-2 2zM7 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zm0-5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            Form
                        </a>
                    </li>

                </ul>
            </li>

            {{-- MENU ADJUSTMENTS --}}
            <li id="adjustment" class="d-none">
                <a href="#adjustmentSubmenu" id="adjustmentLabel" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle collapsed">Adjustments</a>
                <ul class="collapse list-unstyled" id="adjustmentSubmenu">

                    {{-- LIST ADJUSTMENTS --}}
                    <li class="{{ Request::routeIs('list-adjustments') ? 'active' : '' }} d-none" id="list-adjustments">
                        <a href="/list-adjustments">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                            </svg>
                            List
                        </a>
                    </li>

                    {{-- FORM ADJUSTMENTS --}}
                    <li class="{{ Request::routeIs('form-adjustments') ? 'active' : '' }} d-none" id="form-adjustments">
                        <a href="/form-adjustments">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks" viewBox="0 0 16 16">
                                <path d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zM2 1a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm0 8a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2H2zm.854-3.646a.5.5 0 0 1-.708 0l-1-1a.5.5 0 1 1 .708-.708l.646.647 1.646-1.647a.5.5 0 1 1 .708.708l-2 2zm0 8a.5.5 0 0 1-.708 0l-1-1a.5.5 0 0 1 .708-.708l.646.647 1.646-1.647a.5.5 0 0 1 .708.708l-2 2zM7 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zm0-5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            Form
                        </a>
                    </li>

                </ul>
            </li>

            {{-- MENU RETURN --}}
            <li id="return" class="d-none">
                <a href="#returnSubmenu" id="returnLabel" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle collapsed">Return</a>
                <ul class="collapse list-unstyled" id="returnSubmenu">

                    {{-- LIST RETURN --}}
                    <li class="{{ Request::routeIs('list-return') ? 'active' : '' }} d-none" id="list-return">
                        <a href="/list-return">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                            </svg>
                            List
                        </a>
                    </li>

                    {{-- FORM RETURN --}}
                    <li class="{{ Request::routeIs('form-return') ? 'active' : '' }} d-none" id="form-return">
                        <a href="/form-return">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks" viewBox="0 0 16 16">
                                <path d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zM2 1a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm0 8a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2H2zm.854-3.646a.5.5 0 0 1-.708 0l-1-1a.5.5 0 1 1 .708-.708l.646.647 1.646-1.647a.5.5 0 1 1 .708.708l-2 2zm0 8a.5.5 0 0 1-.708 0l-1-1a.5.5 0 0 1 .708-.708l.646.647 1.646-1.647a.5.5 0 0 1 .708.708l-2 2zM7 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1zm0-5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            Form
                        </a>
                    </li>

                </ul>
            </li>

            {{-- MENU ADMINISTRATION --}}
            <li id="administration">
                <a href="#administrationSubmenu" id="administrationLabel" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle collapsed">Administration</a>
                <ul class="collapse list-unstyled show" id="administrationSubmenu">

                    {{-- CHANGE PASSWORD --}}
                    <li>
                        <a href="{{ route('change-password') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                            </svg>
                            Change Password
                        </a>
                    </li>
                    {{-- LOGOUT --}}
                    <li id="logout">
                        <form action="/logout" method="post">
                            @csrf
                            <button class="btnLogout" data-toggle="collapse" type="submit">Logout</button>
                        </form>
                        {{-- <a href="/logout" data-toggle="collapse" aria-expanded="false">Logout</a> --}}
                    </li>

                </ul>
            </li>



        </ul>
        <div class="footer">
            <p>
                @php
                    $listMenu = Session::get('listMenu');
                @endphp
                {{-- @php
                    $listMenu = Session::get('listMenu');
                    echo "hai: " . $listMenu;
                @endphp
                @foreach ( $listMenu as $menu )
                    <li id="master">
                        <a href="#masterSubmenu" id="masterLabel" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle collapsed">Data Master</a>
                        <ul class="collapse list-unstyled show" id="masterSubmenu">

                            <li class="{{ Request::routeIs('master-user') ? 'active' : '' }}" id="master-user">
                                <a href="/master-user">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                    </svg>
                                    Users
                                </a>
                            </li>

                        </ul>
                    </li>
                @endforeach --}}
            </p>
            <p style="margin-top: 20px; font-size: 11.6px">
                &copy; 1TCS <script>document.write(new Date().getFullYear());</script>. All rights reserved.
            </p>
        </div>
    </div>
</nav>

<script>

    // const listMenu = <?php $listMenu = Session::get('listMenu'); ?>;

    const home = document.getElementById("home");
    const master = document.getElementById("master");
    const receiving = document.getElementById("receiving");
    const expending = document.getElementById("expending");
    const transfer = document.getElementById("transfer");
    const stock = document.getElementById("stock");
    const stockOpname = document.getElementById("stockOpname");
    const adjustment = document.getElementById("adjustment");
    const retur = document.getElementById("return");
    const administration = document.getElementById("administration");

    const masterSub = document.getElementById("masterSubmenu");
    const masterLab = document.getElementById("masterLabel");
    const masterUser = document.getElementById("master-user");
    const masterProfile = document.getElementById("master-profile");
    const masterSite = document.getElementById("master-site");
    const masterLocation = document.getElementById("master-location");
    const masterProduct = document.getElementById("master-product-category");
    const masterSupplier = document.getElementById("master-supplier");

    const recSub = document.getElementById("receivingSubmenu");
    const recLab = document.getElementById("receivingLabel");
    const recList = document.getElementById("list-receiving");
    const recForm = document.getElementById("form-receiving");

    const expSub = document.getElementById("expendingSubmenu");
    const expLab = document.getElementById("expendingLabel");
    const expList = document.getElementById("list-expending");
    const expForm = document.getElementById("form-expending");

    const trfSub = document.getElementById("transferSubmenu");
    const trfLab = document.getElementById("transferLabel");
    const trfList = document.getElementById("list-transfer");
    const trfForm = document.getElementById("form-transfer");

    const stockSub = document.getElementById("stockSubmenu");
    const stockLab = document.getElementById("stockLabel");
    const stkList = document.getElementById("list-stock");
    const stkMovement = document.getElementById("movement-stock");

    const stockOpnameSub = document.getElementById("stockOpnameSubmenu");
    const stockOpnameLab = document.getElementById("stockOpnameLabel");
    const stkOpnList = document.getElementById("list-stock-opname");
    const stkOpnForm = document.getElementById("form-stock-opname");

    const adjustmentSub = document.getElementById("adjustmentSubmenu");
    const adjustmentLab = document.getElementById("adjustmentLabel");
    const adjList = document.getElementById("list-adjustments");
    const adjForm = document.getElementById("form-adjustments");

    const returnSub = document.getElementById("returnSubmenu");
    const returnLab = document.getElementById("returnLabel");
    const rtnList = document.getElementById("list-return");
    const rtnForm = document.getElementById("form-return");

    const administrationSub = document.getElementById("administrationSubmenu");
    const administrationLab = document.getElementById("administrationLabel");


    $(document).ready(function () {

        const data = <?php echo $listMenu ?>;

        data.forEach(function(element) {
            // console.log(element.menu_name);
            if ( element.menu_name == "Data Master" ) {
                master.classList.remove("d-none");
                if ( element.sub_menu_name == "Users" ) {
                    masterUser.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Profiles" ) {
                    masterProfile.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Sites" ) {
                    masterSite.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Locations" ) {
                    masterLocation.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Product Categories" ) {
                    masterProduct.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Suppliers" ) {
                    masterSupplier.classList.remove("d-none");
                }
            } else if ( element.menu_name == "Receiving" ) {
                receiving.classList.remove("d-none");
                if ( element.sub_menu_name == "List" ) {
                    recList.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Form" ) {
                    recForm.classList.remove("d-none");
                }
            } else if ( element.menu_name == "Expending" ) {
                expending.classList.remove("d-none");
                if ( element.sub_menu_name == "List" ) {
                    expList.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Form" ) {
                    expForm.classList.remove("d-none");
                }
            } else if ( element.menu_name == "Transfer" ) {
                transfer.classList.remove("d-none");
                if ( element.sub_menu_name == "List" ) {
                    trfList.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Form" ) {
                    trfForm.classList.remove("d-none");
                }
            } else if ( element.menu_name == "Stock" ) {
                stock.classList.remove("d-none");
                if ( element.sub_menu_name == "List" ) {
                    stkList.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Movement" ) {
                    stkMovement.classList.remove("d-none");
                }
            } else if ( element.menu_name == "Stock Opname" ) {
                stockOpname.classList.remove("d-none");
                if ( element.sub_menu_name == "List" ) {
                    stkOpnList.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Form" ) {
                    stkOpnForm.classList.remove("d-none");
                }
            } else if ( element.menu_name == "Adjustments" ) {
                adjustment.classList.remove("d-none");
                if ( element.sub_menu_name == "List" ) {
                    adjList.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Form" ) {
                    adjForm.classList.remove("d-none");
                }
            } else if ( element.menu_name == "Return" ) {
                retur.classList.remove("d-none");
                if ( element.sub_menu_name == "List" ) {
                    rtnList.classList.remove("d-none");
                } else if ( element.sub_menu_name == "Form" ) {
                    rtnForm.classList.remove("d-none");
                }
            }

        });

        // ===== MENU MASTER =====
        $("#masterLabel").attr("aria-expanded",false);
        masterLab.classList.add('collapsed');
        masterSub.classList.remove('show');

        // ===== MENU RECEIVING =====
        $("#receivingLabel").attr("aria-expanded",false);
        recLab.classList.add('collapsed');
        recSub.classList.remove('show');


        // ===== MENU EXPENDING =====
        $("#expendingLabel").attr("aria-expanded",false);
        expLab.classList.add('collapsed');
        expSub.classList.remove('show');


        // ===== MENU TRANSFER =====
        $("#transferLabel").attr("aria-expanded",false);
        trfLab.classList.add('collapsed');
        trfSub.classList.remove('show');


        // ===== MENU STOCK =====
        $("#stockLabel").attr("aria-expanded",false);
        stockLab.classList.add('collapsed');
        stockSub.classList.remove('show');


        // ===== MENU STOCK OPNAME =====
        $("#stockOpnameLabel").attr("aria-expanded",false);
        stockOpnameLab.classList.add('collapsed');
        stockOpnameSub.classList.remove('show');


        // ===== MENU ADJUSTMENT =====
        $("#adjustmentLabel").attr("aria-expanded",false);
        adjustmentLab.classList.add('collapsed');
        adjustmentSub.classList.remove('show');

        // ===== MENU ADMINISTRATION =====
        $("#administrationLabel").attr("aria-expanded",false);
        administrationLab.classList.add('collapsed');
        administrationSub.classList.remove('show');

    });

    // $('#master').click(function() {

    //     master.classList.add('active');
    //     receiving.classList.remove('active');
    //     expending.classList.remove('active');
    //     transfer.classList.remove('active');
    //     stock.classList.remove('active');
    //     stockOpname.classList.remove('active');
    //     adjustment.classList.remove('active');
    //     administration.classList.remove('active');

    //     // ===== MENU RECEIVING =====
    //     $("#receivingLabel").attr("aria-expanded",false);
    //     recLab.classList.add('collapsed');
    //     recSub.classList.remove('show');


    //     // ===== MENU EXPENDING =====
    //     $("#expendingLabel").attr("aria-expanded",false);
    //     expLab.classList.add('collapsed');
    //     expSub.classList.remove('show');


    //     // ===== MENU TRANSFER =====
    //     $("#transferLabel").attr("aria-expanded",false);
    //     trfLab.classList.add('collapsed');
    //     trfSub.classList.remove('show');


    //     // ===== MENU STOCK =====
    //     $("#stockLabel").attr("aria-expanded",false);
    //     stockLab.classList.add('collapsed');
    //     stockSub.classList.remove('show');


    //     // ===== MENU STOCK OPNAME =====
    //     $("#stockOpnameLabel").attr("aria-expanded",false);
    //     stockOpnameLab.classList.add('collapsed');
    //     stockOpnameSub.classList.remove('show');


    //     // ===== MENU ADJUSTMENT =====
    //     $("#adjustmentLabel").attr("aria-expanded",false);
    //     adjustmentLab.classList.add('collapsed');
    //     adjustmentSub.classList.remove('show');


    //     // ===== MENU ADMINISTRATION =====
    //     $("#administrationLabel").attr("aria-expanded",false);
    //     administrationLab.classList.add('collapsed');
    //     administrationSub.classList.remove('show');


    // });


    // $('#receiving').click(function() {

    //     master.classList.remove('active');
    //     receiving.classList.add('active');
    //     expending.classList.remove('active');
    //     transfer.classList.remove('active');
    //     stock.classList.remove('active');
    //     stockOpname.classList.remove('active');
    //     adjustment.classList.remove('active');
    //     administration.classList.remove('active');

    //     // ===== MENU MASTER =====
    //     $("#masterLabel").attr("aria-expanded",false);
    //     masterLab.classList.add('collapsed');
    //     masterSub.classList.remove('show');


    //     // ===== MENU EXPENDING =====
    //     $("#expendingLabel").attr("aria-expanded",false);
    //     expLab.classList.add('collapsed');
    //     expSub.classList.remove('show');


    //     // ===== MENU TRANSFER =====
    //     $("#transferLabel").attr("aria-expanded",false);
    //     trfLab.classList.add('collapsed');
    //     trfSub.classList.remove('show');


    //     // ===== MENU STOCK =====
    //     $("#stockLabel").attr("aria-expanded",false);
    //     stockLab.classList.add('collapsed');
    //     stockSub.classList.remove('show');


    //     // ===== MENU STOCK OPNAME =====
    //     $("#stockOpnameLabel").attr("aria-expanded",false);
    //     stockOpnameLab.classList.add('collapsed');
    //     stockOpnameSub.classList.remove('show');


    //     // ===== MENU ADJUSTMENT =====
    //     $("#adjustmentLabel").attr("aria-expanded",false);
    //     adjustmentLab.classList.add('collapsed');
    //     adjustmentSub.classList.remove('show');


    //     // ===== MENU ADMINISTRATION =====
    //     $("#administrationLabel").attr("aria-expanded",false);
    //     administrationLab.classList.add('collapsed');
    //     administrationSub.classList.remove('show');


    // });


    // $('#expending').click(function() {

    //     master.classList.remove('active');
    //     receiving.classList.remove('active');
    //     expending.classList.add('active');
    //     transfer.classList.remove('active');
    //     stock.classList.remove('active');
    //     stockOpname.classList.remove('active');
    //     adjustment.classList.remove('active');
    //     administration.classList.remove('active');

    //     // ===== MENU MASTER =====
    //     $("#masterLabel").attr("aria-expanded",false);
    //     masterLab.classList.add('collapsed');
    //     masterSub.classList.remove('show');


    //     // ===== MENU RECEIVING =====
    //     $("#receivingLabel").attr("aria-expanded",false);
    //     recLab.classList.add('collapsed');
    //     recSub.classList.remove('show');


    //     // ===== MENU TRANSFER =====
    //     $("#transferLabel").attr("aria-expanded",false);
    //     trfLab.classList.add('collapsed');
    //     trfSub.classList.remove('show');


    //     // ===== MENU STOCK =====
    //     $("#stockLabel").attr("aria-expanded",false);
    //     stockLab.classList.add('collapsed');
    //     stockSub.classList.remove('show');


    //     // ===== MENU STOCK OPNAME =====
    //     $("#stockOpnameLabel").attr("aria-expanded",false);
    //     stockOpnameLab.classList.add('collapsed');
    //     stockOpnameSub.classList.remove('show');


    //     // ===== MENU ADJUSTMENT =====
    //     $("#adjustmentLabel").attr("aria-expanded",false);
    //     adjustmentLab.classList.add('collapsed');
    //     adjustmentSub.classList.remove('show');


    //     // ===== MENU ADMINISTRATION =====
    //     $("#administrationLabel").attr("aria-expanded",false);
    //     administrationLab.classList.add('collapsed');
    //     administrationSub.classList.remove('show');


    // });


    // $('#transfer').click(function() {

    //     master.classList.remove('active');
    //     receiving.classList.remove('active');
    //     expending.classList.remove('active');
    //     transfer.classList.add('active');
    //     stock.classList.remove('active');
    //     stockOpname.classList.remove('active');
    //     adjustment.classList.remove('active');
    //     administration.classList.remove('active');

    //     // ===== MENU MASTER =====
    //     $("#masterLabel").attr("aria-expanded",false);
    //     masterLab.classList.add('collapsed');
    //     masterSub.classList.remove('show');


    //     // ===== MENU RECEIVING =====
    //     $("#receivingLabel").attr("aria-expanded",false);
    //     recLab.classList.add('collapsed');
    //     recSub.classList.remove('show');


    //     // ===== MENU EXPENDING =====
    //     $("#expendingLabel").attr("aria-expanded",false);
    //     expLab.classList.add('collapsed');
    //     expSub.classList.remove('show');


    //     // ===== MENU STOCK =====
    //     $("#stockLabel").attr("aria-expanded",false);
    //     stockLab.classList.add('collapsed');
    //     stockSub.classList.remove('show');


    //     // ===== MENU STOCK OPNAME =====
    //     $("#stockOpnameLabel").attr("aria-expanded",false);
    //     stockOpnameLab.classList.add('collapsed');
    //     stockOpnameSub.classList.remove('show');


    //     // ===== MENU ADJUSTMENT =====
    //     $("#adjustmentLabel").attr("aria-expanded",false);
    //     adjustmentLab.classList.add('collapsed');
    //     adjustmentSub.classList.remove('show');


    //     // ===== MENU ADMINISTRATION =====
    //     $("#administrationLabel").attr("aria-expanded",false);
    //     administrationLab.classList.add('collapsed');
    //     administrationSub.classList.remove('show');


    // });


    // $('#stock').click(function() {

    //     master.classList.remove('active');
    //     receiving.classList.remove('active');
    //     expending.classList.remove('active');
    //     transfer.classList.remove('active');
    //     stock.classList.add('active');
    //     stockOpname.classList.remove('active');
    //     adjustment.classList.remove('active');
    //     administration.classList.remove('active');

    //     // ===== MENU MASTER =====
    //     $("#masterLabel").attr("aria-expanded",false);
    //     masterLab.classList.add('collapsed');
    //     masterSub.classList.remove('show');


    //     // ===== MENU RECEIVING =====
    //     $("#receivingLabel").attr("aria-expanded",false);
    //     recLab.classList.add('collapsed');
    //     recSub.classList.remove('show');


    //     // ===== MENU EXPENDING =====
    //     $("#expendingLabel").attr("aria-expanded",false);
    //     expLab.classList.add('collapsed');
    //     expSub.classList.remove('show');


    //     // ===== MENU TRANSFER =====
    //     $("#transferLabel").attr("aria-expanded",false);
    //     trfLab.classList.add('collapsed');
    //     trfSub.classList.remove('show');


    //     // ===== MENU STOCK OPNAME =====
    //     $("#stockOpnameLabel").attr("aria-expanded",false);
    //     stockOpnameLab.classList.add('collapsed');
    //     stockOpnameSub.classList.remove('show');


    //     // ===== MENU ADJUSTMENT =====
    //     $("#adjustmentLabel").attr("aria-expanded",false);
    //     adjustmentLab.classList.add('collapsed');
    //     adjustmentSub.classList.remove('show');


    //     // ===== MENU ADMINISTRATION =====
    //     $("#administrationLabel").attr("aria-expanded",false);
    //     administrationLab.classList.add('collapsed');
    //     administrationSub.classList.remove('show');


    // });


    // $('#stockOpname').click(function() {

    //     master.classList.remove('active');
    //     receiving.classList.remove('active');
    //     expending.classList.remove('active');
    //     transfer.classList.remove('active');
    //     stock.classList.remove('active');
    //     stockOpname.classList.add('active');
    //     adjustment.classList.remove('active');
    //     administration.classList.remove('active');

    //     // ===== MENU MASTER =====
    //     $("#masterLabel").attr("aria-expanded",false);
    //     masterLab.classList.add('collapsed');
    //     masterSub.classList.remove('show');


    //     // ===== MENU RECEIVING =====
    //     $("#receivingLabel").attr("aria-expanded",false);
    //     recLab.classList.add('collapsed');
    //     recSub.classList.remove('show');


    //     // ===== MENU EXPENDING =====
    //     $("#expendingLabel").attr("aria-expanded",false);
    //     expLab.classList.add('collapsed');
    //     expSub.classList.remove('show');


    //     // ===== MENU TRANSFER =====
    //     $("#transferLabel").attr("aria-expanded",false);
    //     trfLab.classList.add('collapsed');
    //     trfSub.classList.remove('show');


    //     // ===== MENU STOCK =====
    //     $("#stockLabel").attr("aria-expanded",false);
    //     stockLab.classList.add('collapsed');
    //     stockSub.classList.remove('show');


    //     // ===== MENU ADJUSTMENT =====
    //     $("#adjustmentLabel").attr("aria-expanded",false);
    //     adjustmentLab.classList.add('collapsed');
    //     adjustmentSub.classList.remove('show');


    //     // ===== MENU ADMINISTRATION =====
    //     $("#administrationLabel").attr("aria-expanded",false);
    //     administrationLab.classList.add('collapsed');
    //     administrationSub.classList.remove('show');


    // });


    // $('#adjustment').click(function() {

    //     master.classList.remove('active');
    //     receiving.classList.remove('active');
    //     expending.classList.remove('active');
    //     transfer.classList.remove('active');
    //     stock.classList.remove('active');
    //     stockOpname.classList.remove('active');
    //     adjustment.classList.add('active');
    //     administration.classList.remove('active');

    //     // ===== MENU MASTER =====
    //     $("#masterLabel").attr("aria-expanded",false);
    //     masterLab.classList.add('collapsed');
    //     masterSub.classList.remove('show');


    //     // ===== MENU RECEIVING =====
    //     $("#receivingLabel").attr("aria-expanded",false);
    //     recLab.classList.add('collapsed');
    //     recSub.classList.remove('show');


    //     // ===== MENU EXPENDING =====
    //     $("#expendingLabel").attr("aria-expanded",false);
    //     expLab.classList.add('collapsed');
    //     expSub.classList.remove('show');


    //     // ===== MENU TRANSFER =====
    //     $("#transferLabel").attr("aria-expanded",false);
    //     trfLab.classList.add('collapsed');
    //     trfSub.classList.remove('show');


    //     // ===== MENU STOCK =====
    //     $("#stockLabel").attr("aria-expanded",false);
    //     stockLab.classList.add('collapsed');
    //     stockSub.classList.remove('show');


    //     // ===== MENU STOCK OPNAME =====
    //     $("#stockOpnameLabel").attr("aria-expanded",false);
    //     stockOpnameLab.classList.add('collapsed');
    //     stockOpnameSub.classList.remove('show');


    //     // ===== MENU ADMINISTRATION =====
    //     $("#administrationLabel").attr("aria-expanded",false);
    //     administrationLab.classList.add('collapsed');
    //     administrationSub.classList.remove('show');


    // });

    // $('#administration').click(function() {

    //     master.classList.remove('active');
    //     receiving.classList.remove('active');
    //     expending.classList.remove('active');
    //     transfer.classList.remove('active');
    //     stock.classList.remove('active');
    //     stockOpname.classList.remove('active');
    //     adjustment.classList.remove('active');
    //     administration.classList.add('active');

    //     // ===== MENU MASTER =====
    //     $("#masterLabel").attr("aria-expanded",false);
    //     masterLab.classList.add('collapsed');
    //     masterSub.classList.remove('show');


    //     // ===== MENU RECEIVING =====
    //     $("#receivingLabel").attr("aria-expanded",false);
    //     recLab.classList.add('collapsed');
    //     recSub.classList.remove('show');


    //     // ===== MENU EXPENDING =====
    //     $("#expendingLabel").attr("aria-expanded",false);
    //     expLab.classList.add('collapsed');
    //     expSub.classList.remove('show');


    //     // ===== MENU TRANSFER =====
    //     $("#transferLabel").attr("aria-expanded",false);
    //     trfLab.classList.add('collapsed');
    //     trfSub.classList.remove('show');


    //     // ===== MENU STOCK =====
    //     $("#stockLabel").attr("aria-expanded",false);
    //     stockLab.classList.add('collapsed');
    //     stockSub.classList.remove('show');


    //     // ===== MENU STOCK OPNAME =====
    //     $("#stockOpnameLabel").attr("aria-expanded",false);
    //     stockOpnameLab.classList.add('collapsed');
    //     stockOpnameSub.classList.remove('show');


    // });


    // GLOBAL SETUP CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    // $('#changePw').click(async function() {
    //     const { value: password } = await Swal.fire({
    //         title: "Change Password",
    //         input: "password",
    //         inputLabel: "Password",
    //         inputPlaceholder: "Enter your new password",
    //         inputAttributes: {
    //             maxlength: "10",
    //             autocapitalize: "off",
    //             autocorrect: "off"
    //         }
    //     });
    //     if (password) {
    //         Swal.fire(`Entered password: ${password}`);
    //     }
    // });
</script>
